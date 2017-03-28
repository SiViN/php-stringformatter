<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <https://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.6.0
 */

namespace m36\StringFormatter;

/**
 * Class Compiler.
 *
 * @internal
 */
class Compiler
{
    const MODE_INDEX = 'index';
    const MODE_NAMED = 'named';

    const TRACE_LEVEL_MAX = 6;

    /**
     * Matrix for mapping string suffixes to values provided for base_convert function.
     *
     * @var array
     */
    protected static $matrix__base_convert = array(
        'b' => 2,
        'o' => 8,
        'd' => 10,
        'x' => 16,
        'X' => 16,
    );

    /**
     * Matrix for mapping string suffixes to values provided for str_pad function.
     *
     * @var array
     */
    protected static $matrix__str_pad = array(
        '<' => STR_PAD_RIGHT,
        '>' => STR_PAD_LEFT,
        '^' => STR_PAD_BOTH,
    );

    /**
     * Regular expressions for key used in template.
     *
     * Key must be one of Compiler::MODE_* constant, and value is regular expression used to find key in tokens
     *
     * @var array
     */
    protected static $rxp_keys = array(
        self::MODE_INDEX => '\d*',
        self::MODE_NAMED => '\w+',
    );

    /**
     * Regular expression for finding tokens in format.
     *
     * @var string
     */
    protected static $rxp_token = '
        /
        \{              # opening brace
            (
                [^}]*   # all but closing brace
            )
        \}              # closing brace
    /x';

    /** @var array */
    protected $traceClass;

    /** @var array */
    protected $traceFile;

    /**
     * Mode we are run.
     *
     * @var int one of: Compiler::MODE_NORMAL, Compiler::MODE_NAMED
     */
    protected $mode;

    /**
     * Pointer for accessing given elements when no placeholder in format is given.
     *
     * @var int
     */
    protected $pointer = 0;

    /**
     * @var string
     */
    protected $format;

    /**
     * @var array
     */
    protected $params;

    /** @var string */
    protected $compiledResult;

    /**
     * Compiler constructor.
     *
     * @param string $format
     * @param array  $params
     * @param string $mode       Compiler::MODE_
     */
    public function __construct($format, $params, $mode)
    {
        $this->format = $format;
        $this->params = $params;
        $this->mode = $mode;

        $this->findTraces();
    }

    protected function findTraces()
    {
        if (\version_compare(PHP_VERSION, '5.4.0', '<')) {
            $trace = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        } else {
            $trace = \debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, static::TRACE_LEVEL_MAX);
        }

        foreach ($trace as $itemIdx => $traceItem) {
            if ($this->traceFile) {
                $this->traceClass = $traceItem;
                break;
            }

            if (
                in_array($traceItem['function'], array('m36\StringFormatter\iformat', 'm36\StringFormatter\iformatl', 'm36\StringFormatter\nformat')) &&
                !isset($traceItem['class'])
            ) {
                $this->traceFile = $traceItem;
            } else if (
                $traceItem['function'] == 'compile' &&
                in_array($traceItem['class'], array('m36\StringFormatter\FormatterIndex', 'm36\StringFormatter\FormatterNamed'))
            ) {
                $this->traceFile = $traceItem;
            } else if (
                $traceItem['function'] == 'unfold' &&
                $traceItem['class'] == 'm36\StringFormatter\TransformerBuilder'
            ) {
                if (
                    isset($trace[$itemIdx + 1]) &&
                    $trace[$itemIdx + 1]['function'] == '__toString' &&
                    $traceItem['class'] == 'm36\StringFormatter\TransformerBuilder'
                ) {
                    // pass
                } else {
                    $this->traceFile = $traceItem;
                }

            } else if (
                $traceItem['function'] == '__toString' &&
                $traceItem['class'] == 'm36\StringFormatter\TransformerBuilder'
            ) {
                $this->traceFile = $traceItem;
            }
        }
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Helper function - test for existence of key in given parameters.
     *
     * @param string|int $key
     *
     * @return bool
     */
    protected function hasKey($key)
    {
        return ($this->mode == self::MODE_INDEX && $key == '') || \array_key_exists($key, $this->params);
    }

    /**
     * Helper function for find current param.
     *
     * @param int $key parameter index (optional)
     *
     * @return mixed
     */
    protected function getParam($key = '')
    {
        if ($key === '') {
            $key = $this->pointer++;
        }

        return $this->params[$key];
    }

    /**
     * Callback for preg_replace_callback - here is doing all magic with replacing format token with
     * proper values from given params.
     *
     * @param array $data matched token data
     *
     * @return string
     */
    protected function formatCallback($data)
    {
        if (\count($data) < 2) {
            return $data[0];
        }

        // simple auto or explicit placeholder
        if ($this->mode == self::MODE_INDEX && $this->hasKey($data[1])) {
            return $this->getParam($data[1]);
        }

        // simple named, explicit placeholder
        elseif ($this->mode == self::MODE_NAMED && \strlen($data[1]) > 0 && $this->hasKey($data[1])) {
            return $this->getParam($data[1]);
        }

        // keywords
        elseif (\preg_match('
            /
            ^
                @
                (class|classLong|method|methodLong|function|file|fileLong|dir|dirLong|line)
            $
            /x', $data[1], $match)
        ) {
            switch ($match[1]) {
                case 'classLong':
                    if (!$this->traceClass || !isset($this->traceClass['class'])) {
                        $msg = "Bad usage of @classLong keyword in {$this->traceFile['file']} on " .
                            "line {$this->traceFile['line']} (by m36\\StringFormatter)";
                        \trigger_error($msg, E_USER_WARNING);

                        return '';
                    }

                    return $this->traceClass['class'];
                case 'class':
                    if (!$this->traceClass || !isset($this->traceClass['class'])) {
                        $msg = "Bad usage of @class keyword in {$this->traceFile['file']} on " .
                            "line {$this->traceFile['line']} (by m36\\StringFormatter)";
                        \trigger_error($msg, E_USER_WARNING);

                        return '';
                    }

                    $cls = \explode('\\', $this->traceClass['class']);

                    return end($cls);
                case 'method':
                    if (!$this->traceClass || !isset($this->traceClass['class'])) {
                        $msg = "Bad usage of @method keyword in {$this->traceFile['file']} on " .
                            "line {$this->traceFile['line']} (by m36\\StringFormatter)";
                        \trigger_error($msg, E_USER_WARNING);

                        return '';
                    }

                    $cls = \explode('\\', $this->traceClass['class']);
                    $cls = \end($cls);

                    return $cls . '::' . $this->traceClass['function'];
                case 'methodLong':
                    if (!$this->traceClass || !isset($this->traceClass['class'])) {
                        $msg = "Bad usage of @methodLong keyword in {$this->traceFile['file']} on " .
                            "line {$this->traceFile['line']} (by m36\\StringFormatter)";
                        \trigger_error($msg, E_USER_WARNING);

                        return '';
                    }

                    return $this->traceClass['class'] . '::' . $this->traceClass['function'];
                case 'function':
                    if (!$this->traceClass || !isset($this->traceClass['function'])) {
                        $msg = "Bad usage of @function keyword in {$this->traceFile['file']} on " .
                            "line {$this->traceFile['line']} (by m36\\StringFormatter)";
                        \trigger_error($msg, E_USER_WARNING);

                        return '';
                    }

                    return $this->traceClass['function'];
                case 'file':
                    return \basename($this->traceFile['file']);
                case 'fileLong':
                    return $this->traceFile['file'];
                case 'dir':
                    return \basename(\dirname($this->traceFile['file']));
                case 'dirLong':
                    return \dirname($this->traceFile['file']);
                case 'line':
                    return $this->traceFile['line'];
            }
        }

        // text alignment
        elseif (\preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                :                                       # explicit colon
                (.)?                                    # pad character
                ([<>^])                                 # alignment
                (\d+)                                   # pad length
            $
            /x', $data[1], $match) &&
            $this->hasKey($match[1])
        ) {
            return \str_pad(
                $this->getParam($match[1]),
                $match[4],
                (\strlen($match[2]) > 0 ? $match[2] : ' '),
                static::$matrix__str_pad[$match[3]]
            );
        }

        // sprintf pattern
        elseif (\preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                %                                       # explicit percent
                (.*)                                    # sprintf pattern
            $
            /x', $data[1], $match) &&
            $this->hasKey($match[1])
        ) {
            return \vsprintf($match[2], $this->getParam($match[1]));
        }

        // call object method or get object property
        elseif (\preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                ->                                      # explicit arrow
                (\w+)                                   # keyword (field or method name)
            $
            /x', $data[1], $match) &&
            $this->hasKey($match[1])
        ) {
            $param = $this->getParam($match[1]);
            if (\method_exists($param, $match[2])) {
                return \call_user_func(array($param, $match[2]));
            } elseif (\property_exists($param, $match[2])) {
                return $param->{$match[2]};
            } elseif (\in_array('__call', \get_class_methods($param))) {
                return \call_user_func(array($param, $match[2]));
            } elseif (\in_array('__get', \get_class_methods($param))) {
                return $param->{$match[2]};
            } else {
                return $data[0];
            }
        }

        // converting int to other base
        elseif (\preg_match('
            /
            ^
            (' . self::$rxp_keys[$this->mode] . ')  # placeholder
            [#]                                     # explicit hash
            (?:
                (\d+)                               # source base
                [#]                                 # explicit hash
            )?
            ([dxXob]|\d\d?)                         # destination base
            $
            /x', $data[1], $match) &&
            $this->hasKey($match[1])
        ) {
            $ret = \base_convert(
                $this->getParam($match[1]),                         // value to convert
                ($match[2] ? $match[2] : 10),                       // source base (defaults to 10)
                (
                    \is_numeric($match[3])                          // destination base is:
                        ? $match[3]                                 // - numeric
                        : self::$matrix__base_convert[$match[3]]    // - or named
                )
            );
            if ($match[3] == 'X') {
                $ret = \strtoupper($ret);
            }

            return $ret;
        }

        // array index
        elseif (\preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                \[                                      # opening square bracket
                    (\w+)                               # key
                \]                                      # closing square bracket
            $
            /x', $data[1], $match) &&
            $this->hasKey($match[1]) &&
            \is_array($ret = $this->getParam($match[1])) &&
            isset($ret[$match[2]])
        ) {
            return $ret[$match[2]];
        }

        // unknown token
        else {
            $msg = "Unknown token found in format: {$data[0]} in {$this->traceFile['file']} on " .
                "line {$this->traceFile['line']} (by m36\\StringFormatter)";
            \trigger_error($msg, E_USER_WARNING);

            return $data[0];
        }
    }

    /**
     * Compile $this->format and fill it's placeholders with data from $this->params.
     *
     * @return string
     */
    public function run()
    {
        if (is_null($this->compiledResult)) {
            $this->compiledResult = \preg_replace_callback(static::$rxp_token, array($this, 'formatCallback'), $this->format);
        }

        return $this->compiledResult;
    }
}
