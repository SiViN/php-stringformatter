<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <http://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.5.0
 */

namespace m36\StringFormatter;

class Compiler
{
    const MODE_INDEX = 'index';
    const MODE_NAMED = 'named';

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
     * Key must be one of StringFormatter::MODE_* constant, and value is regular expression used to find key in tokens
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

    /**
     * Result of debug_backtrace().
     *
     * @var array
     */
    protected $trace;

    /**
     * How long trace should be generated.
     *
     * @var int
     */
    protected $traceLevel;

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

    /**
     * Compiler constructor.
     *
     * @param string $format
     * @param array  $params
     * @param string $mode       Compiler::MODE_
     * @param int    $traceLevel
     */
    public function __construct($format, $params, $mode, $traceLevel)
    {
        $this->format = $format;
        $this->params = $params;
        $this->mode = $mode;
        $this->traceLevel = $traceLevel;
    }

    /**
     * Helper function - test for existence of key in given parameters.
     *
     * @param string|int $key
     *
     * @return bool
     */
    protected function has_key($key)
    {
        return ($this->mode == self::MODE_INDEX && $key == '') || array_key_exists($key, $this->params);
    }

    /**
     * Helper function for find current param.
     *
     * @param int $key parameter index (optional)
     *
     * @return mixed
     */
    protected function get_param($key = '')
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
    protected function format_callback($data)
    {
        if (count($data) < 2) {
            return $data[0];
        }

        // simple auto or explicit placeholder
        if ($this->mode == self::MODE_INDEX && $this->has_key($data[1])) {
            return $this->get_param($data[1]);
        }

        // simple named, explicit placeholder
        elseif ($this->mode == self::MODE_NAMED && strlen($data[1]) > 0 && $this->has_key($data[1])) {
            return $this->get_param($data[1]);
        } elseif (preg_match('
            /
            ^
                @
                (class|classLong|method|methodLong|function|file|fileLong|dir|dirLong|line)
            $
            /x', $data[1], $match)
        ) {
            $classIdx = $this->traceLevel - 1;
            $fileIdx = $this->traceLevel - 2;
            switch ($match[1]) {
                case 'classLong':
                    return $this->trace[$classIdx]['class'];
                case 'class':
                    $cls = explode('\\', $this->trace[$classIdx]['class']);

                    return end($cls);
                case 'method':
                    $cls = explode('\\', $this->trace[$classIdx]['class']);
                    $cls = end($cls);

                    return $cls . '::' . $this->trace[$classIdx]['function'];
                case 'methodLong':
                    return $this->trace[$classIdx]['class'] . '::' . $this->trace[$classIdx]['function'];
                case 'function':
                    return $this->trace[$classIdx]['function'];
                case 'file':
                    return basename($this->trace[$fileIdx]['file']);
                case 'fileLong':
                    return $this->trace[$fileIdx]['file'];
                case 'dir':
                    return basename(dirname($this->trace[$fileIdx]['file']));
                case 'dirLong':
                    return dirname($this->trace[$fileIdx]['file']);
                case 'line':
                    return $this->trace[$fileIdx]['line'];
            }
        }

        // text alignment
        elseif (preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                :                                       # explicit colon
                (.)?                                    # pad character
                ([<>^])                                 # alignment
                (\d+)                                   # pad length
            $
            /x', $data[1], $match) &&
            $this->has_key($match[1])
        ) {
            return str_pad(
                $this->get_param($match[1]),
                $match[4],
                (strlen($match[2]) > 0 ? $match[2] : ' '),
                static::$matrix__str_pad[$match[3]]
            );
        }

        // sprintf pattern
        elseif (preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                %                                       # explicit percent
                (.*)                                    # sprintf pattern
            $
            /x', $data[1], $match) &&
            $this->has_key($match[1])
        ) {
            return vsprintf($match[2], $this->get_param($match[1]));
        }

        // call object method or get object property
        elseif (preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                ->                                      # explicit arrow
                (\w+)                                   # keyword (field or method name)
            $
            /x', $data[1], $match) &&
            $this->has_key($match[1])
        ) {
            $param = $this->get_param($match[1]);
            if (method_exists($param, $match[2])) {
                return call_user_func(array($param, $match[2]));
            } elseif (property_exists($param, $match[2])) {
                return $param->{$match[2]};
            } elseif (in_array('__call', get_class_methods($param))) {
                return call_user_func(array($param, $match[2]));
            } elseif (in_array('__get', get_class_methods($param))) {
                return $param->{$match[2]};
            } else {
                return $data[0];
            }
        }

        // converting int to other base
        elseif (preg_match('
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
            $this->has_key($match[1])
        ) {
            $ret = base_convert(
                $this->get_param($match[1]),                        // value to convert
                ($match[2] ? $match[2] : 10),                       // source base (defaults to 10)
                (
                    is_numeric($match[3])                           // destination base is:
                        ? $match[3]                                 // - numeric
                        : self::$matrix__base_convert[$match[3]]    // - or named
                )
            );
            if ($match[3] == 'X') {
                $ret = strtoupper($ret);
            }

            return $ret;
        }

        // array index
        elseif (preg_match('
            /
            ^
                (' . self::$rxp_keys[$this->mode] . ')  # placeholder
                \[                                      # opening square bracket
                    (\w+)                               # key
                \]                                      # closing square bracket
            $
            /x', $data[1], $match) &&
            $this->has_key($match[1]) &&
            is_array($ret = $this->get_param($match[1])) &&
            isset($ret[$match[2]])
        ) {
            return $ret[$match[2]];
        }

        // unknown token type
        else {
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
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        } else {
            $this->trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $this->traceLevel);
        }

        $parsed = preg_replace_callback(self::$rxp_token, array($this, 'format_callback'), $this->format);

        return $parsed;
    }
}
