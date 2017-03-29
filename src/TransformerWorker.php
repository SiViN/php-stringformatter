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

class TransformerWorker
{
    /**
     * @var string
     */
    protected $string;

    /**
     * Transformer constructor.
     *
     * @param $string string to transform
     */
    public function __construct($string)
    {
        $this->string = $string;
    }

    protected function hasMbstring()
    {
        return \extension_loaded('mbstring');
    }

    protected function encoding($encoding)
    {
        if (\is_null($encoding) && $this->hasMbstring()) {
            return \mb_internal_encoding();
        }

        return $encoding;
    }

    /**
     * Calls given callable from $fn. As a first argument is passed transformed string
     * from Transformer, then pass there other args from Transformer::transform method.
     *
     * @param mixed    $fn     callable in any format recognized by call_user_func_array
     * @param ...mixed $params params for $fn
     *
     * @return TransformerWorker
     */
    public function transform()
    {
        $args = \func_get_args();
        $fn = \array_shift($args);
        \array_unshift($args, $this->string);

        $this->string = \call_user_func_array($fn, $args);
        return $this;
    }

    /**
     * Wrapper for str_replace.
     *
     * If $to is callable, it's executed with two args:
     *  $from - string to replace from
     *  $trfm - current Transformer instance ($this)
     *
     * @param string $from
     * @param string|callable $to
     *
     * @return TransformerWorker
     */
    public function replace($from, $to)
    {
        if (\is_callable($to)) {
            $to = $to($from, $this->string);
        }

        $this->string = \str_replace($from, $to, $this->string);
        return $this;
    }

    /**
     * Wrapper for str_ireplace.
     *
     * If $to is callable, it's executed with two args:
     *  $from - string to replace from
     *  $trfm - current Transformer instance ($this)
     *
     * @param string $from
     * @param string|callable $to
     *
     * @return TransformerWorker
     */
    public function ireplace($from, $to)
    {
        if (\is_callable($to)) {
            $to = $to($from, $this->string);
        }

        $this->string = \str_ireplace($from, $to, $this->string);
        return $this;
    }

    /**
     * Wrapper for preg_replace or preg_replace_callback (depends on $replacement
     * being callback or not).
     *
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     *
     * @return TransformerWorker
     */
    public function regexReplace($pattern, $replacement, $limit = -1)
    {
        if (\is_callable($replacement)) {
            $rxp_function = '\preg_replace_callback';
        } else {
            $rxp_function = '\preg_replace';
        }

        $this->string = $rxp_function($pattern, $replacement, $this->string, $limit);

        return $this;
    }

    /**
     * Wrapper for trim.
     *
     * @param string $charmask
     *
     * @return TransformerWorker
     */
    public function strip($charmask = " \t\n\r\0\x0B")
    {
        return $this->transform('\trim', $charmask);
    }

    /**
     * Wrapper for ltrim.
     *
     * @param string $charmask
     *
     * @return TransformerWorker
     */
    public function lstrip($charmask = " \t\n\r\0\x0B")
    {
        return $this->transform('\ltrim', $charmask);
    }

    /**
     * Wrapper for rtrim.
     *
     * @param string $charmask
     *
     * @return TransformerWorker
     */
    public function rstrip($charmask = " \t\n\r\0\x0B")
    {
        return $this->transform('\rtrim', $charmask);
    }

    /**
     * Wrapper for strtoupper.
     *
     * @param string|null $encoding
     *
     * @return TransformerWorker
     */
    public function upper($encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\strtoupper');
        }

        $encoding = $this->encoding($encoding);

        return $this->transform('\mb_convert_case', MB_CASE_UPPER, $encoding);
    }

    /**
     * Wrapper for strtolower.
     *
     * @param string|null $encoding
     *
     * @return TransformerWorker
     */
    public function lower($encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\strtolower');
        }

        $encoding = $this->encoding($encoding);

        return $this->transform('\mb_convert_case', MB_CASE_LOWER, $encoding);
    }

    /**
     * Wrapper for ucfirst.
     *
     * @param string|null $encoding
     *
     * @return TransformerWorker
     */
    public function upperFirst($encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\ucfirst');
        }

        $encoding = $this->encoding($encoding);

        $string = \mb_strtoupper(\mb_substr($this->string, 0, 1, $encoding), $encoding);

        $this->string = $string . \mb_substr($this->string, 1, \mb_strlen($this->string, $encoding), $encoding);
        return $this;
    }

    /**
     * Wrapper for lcfirst.
     *
     * @param string|null $encoding
     *
     * @return TransformerWorker
     */
    public function lowerFirst($encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\lcfirst');
        }

        $encoding = $this->encoding($encoding);

        $string = \mb_strtolower(\mb_substr($this->string, 0, 1, $encoding), $encoding);

        $this->string = $string . \mb_substr($this->string, 1, \mb_strlen($this->string, $encoding), $encoding);
        return $this;
    }

    /**
     * Wrapper for ucwords.
     *
     * @param string $delimiters (ignored if php do not handle this parameter)
     *
     * @return TransformerWorker
     */
    public function upperWords($delimiters = null)
    {
        if (is_null($delimiters)) {
            $delimiters = " \t\r\n\f\v";
        }

        if (
            (version_compare(PHP_VERSION, '5.4.32', '>=') && version_compare(PHP_VERSION, '5.5.0', '<')) ||
            version_compare(PHP_VERSION, '5.5.16', '>=')
        ) {
            return $this->transform('ucwords', $delimiters);
        } else {
            return $this->transform('ucwords');
        }
    }

    /**
     * Wrapper for wordwrap.
     *
     * @param int    $width
     * @param string $break
     * @param bool   $cut
     *
     * @return TransformerWorker
     */
    public function wordWrap($width = 75, $break = "\n", $cut = false)
    {
        return $this->transform('\wordwrap', $width, $break, $cut);
    }

    /**
     * Wrapper for mb_substr / substr.
     *
     * @param $start
     * @param int|null $length
     * @param string|null $encoding
     *
     * @return TransformerWorker
     */
    public function substr($start, $length = null, $encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\substr', $start, $length);
        }

        $encoding = $this->encoding($encoding);

        // workaround for php < 5.4.8
        if (is_null($length)) {
            $length = \mb_strlen($this->string, $encoding);
        }

        return $this->transform('\mb_substr', $start, $length, $encoding);
    }

    /**
     * Wrapper for str_repeat.
     *
     * @param int $count
     *
     * @return TransformerWorker
     */
    public function repeat($count)
    {
        return $this->transform('\str_repeat', $count);
    }

    /**
     * Reverse string.
     *
     * @param null $encoding
     *
     * @return TransformerWorker
     */
    public function reverse($encoding = null)
    {
        $reversed = '';

        if (!$this->hasMbstring()) {
            $len = \strlen($this->string);
            for ($i = $len - 1; $i >= 0; --$i) {
                $reversed .= \substr($this->string, $i, 1);
            }
        } else {
            $encoding = $this->encoding($encoding);

            $len = \mb_strlen($this->string, $encoding);
            for ($i = $len - 1; $i >= 0; --$i) {
                $reversed .= \mb_substr($this->string, $i, 1, $encoding);
            }
        }

        $this->string = $reversed;
        return $this;
    }

    /**
     * Squash and unify white characters into single space.
     *
     * @return TransformerWorker
     */
    public function squashWhitechars()
    {
        return $this->regexReplace('/[[:space:]]+/u', ' ')->strip();
    }

    /**
     * Insert given string at $idx.
     *
     * @param string      $substring
     * @param int         $idx
     * @param string|null $encoding
     *
     * @return TransformerWorker
     */
    public function insert($substring, $idx, $encoding = null)
    {
        if ($idx <= 0) {
            return $this->prefix($substring);
        }

        if (!$this->hasMbstring()) {
            $len = \strlen($this->string);
            if ($idx >= $len) {
                return $this->suffix($substring);
            }

            $start = \substr($this->string, 0, $idx);
            $end = \substr($this->string, $idx, $len);
        } else {
            $encoding = $this->encoding($encoding);
            $len = \mb_strlen($this->string, $encoding);
            if ($idx >= $len) {
                return $this->suffix($substring);
            }

            $start = \mb_substr($this->string, 0, $idx, $encoding);
            $end = \mb_substr($this->string, $idx, $len, $encoding);
        }

        $this->string = $start . $substring . $end;
        return $this;
    }

    /**
     * Prepend $substring if string doesn't begin with it.
     *
     * @param $substring
     * @param null $encoding
     *
     * @return TransformerWorker
     */
    public function ensurePrefix($substring, $encoding = null)
    {
        if (!$this->hasMbstring()) {
            $len = \strlen($substring);
            if (\substr($this->string, 0, $len) == $substring) {
                return $this;
            }
        } else {
            $encoding = $this->encoding($encoding);
            $len = \mb_strlen($substring, $encoding);
            if (\mb_substr($this->string, 0, $len, $encoding) == $substring) {
                return $this;
            }
        }

        return $this->prefix($substring);
    }

    /**
     * Append $substring if string doesn't end with it.
     *
     * @param $substring
     * @param null $encoding
     *
     * @return TransformerWorker
     */
    public function ensureSuffix($substring, $encoding = null)
    {
        if (!$this->hasMbstring()) {
            $len = \strlen($substring);
            if (\substr($this->string, -$len) == $substring) {
                return $this;
            }
        } else {
            $encoding = $this->encoding($encoding);

            $len = \mb_strlen($substring, $encoding);
            if (\mb_substr($this->string, -$len, \mb_strlen($this->string, $encoding), $encoding) == $substring) {
                return $this;
            }
        }

        return $this->suffix($substring);
    }

    /**
     * Prepend some string on the beginning.
     *
     * @param string $string
     *
     * @return TransformerWorker
     */
    public function prefix($string)
    {
        $this->string = $string . $this->string;
        return $this;
    }

    /**
     * Append some string to the end.
     *
     * @param string $string
     *
     * @return TransformerWorker
     */
    public function suffix($string)
    {
        $this->string = $this->string . $string;
        return $this;
    }

    /**
     * Append some string to the beginning and to the end.
     *
     * @param string $string
     *
     * @return $this
     */
    public function surround($string)
    {
        $this->string = $string . $this->string . $string;
        return $this;
    }

    /**
     * Adds PHP_EOL to the end of string.
     *
     * @return TransformerWorker
     */
    public function eol()
    {
        $this->string .= PHP_EOL;
        return $this;
    }

    /**
     * Adds \r\n to the end of string.
     *
     * @return TransformerWorker
     */
    public function eolrn()
    {
        $this->string .= "\r\n";
        return $this;
    }

    /**
     * Adds \n to the end of string.
     *
     * @return TransformerWorker
     */
    public function eoln()
    {
        $this->string .= "\n";
        return $this;
    }

    public function __toString()
    {
        return (string) $this->string;
    }
}
