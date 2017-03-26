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

class Transformer
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
     * @return Transformer
     */
    public function transform()
    {
        $args = \func_get_args();
        $fn = \array_shift($args);
        \array_unshift($args, $this->string);

        return new static(\call_user_func_array($fn, $args));
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
     * @return Transformer
     */
    public function replace($from, $to)
    {
        if (\is_callable($to)) {
            $to = $to($from, $this);
        }

        return new static(\str_replace($from, $to, $this->string));
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
     * @return Transformer
     */
    public function ireplace($from, $to)
    {
        if (\is_callable($to)) {
            $to = $to($from, $this);
        }

        return new static(\str_ireplace($from, $to, $this->string));
    }

    /**
     * Wrapper for preg_replace or preg_replace_callback (depends on $replacement
     * being callback or not).
     *
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     *
     * @return Transformer
     */
    public function regexReplace($pattern, $replacement, $limit = -1)
    {
        if (\is_callable($replacement)) {
            $rxp_function = '\preg_replace_callback';
        } else {
            $rxp_function = '\preg_replace';
        }

        $result = $rxp_function($pattern, $replacement, $this->string, $limit);

        return new static($result);
    }

    /**
     * Wrapper for trim.
     *
     * @param string $charmask
     *
     * @return Transformer
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
     * @return Transformer
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
     * @return Transformer
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
     * @return Transformer
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
     * @return Transformer
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
     * @return Transformer
     */
    public function upperFirst($encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\ucfirst');
        }

        $encoding = $this->encoding($encoding);

        $string = \mb_strtoupper(\mb_substr($this->string, 0, 1, $encoding), $encoding);

        return new static($string . \mb_substr($this->string, 1, null, $encoding));
    }

    /**
     * Wrapper for lcfirst.
     *
     * @param string|null $encoding
     *
     * @return Transformer
     */
    public function lowerFirst($encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\lcfirst');
        }

        $encoding = $this->encoding($encoding);

        $string = \mb_strtolower(\mb_substr($this->string, 0, 1, $encoding), $encoding);

        return new static($string . \mb_substr($this->string, 1, null, $encoding));
    }

    /**
     * Wrapper for ucwords.
     *
     * @param string $delimiters (ignored if php do not handle this parameter)
     *
     * @return Transformer
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
     * @return Transformer
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
     * @return Transformer
     */
    public function substr($start, $length = null, $encoding = null)
    {
        if (!$this->hasMbstring()) {
            return $this->transform('\substr', $start, $length);
        }

        $encoding = $this->encoding($encoding);

        return $this->transform('\mb_substr', $start, $length, $encoding);
    }

    /**
     * Wrapper for str_repeat.
     *
     * @param int $count
     *
     * @return Transformer
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
     * @return Transformer
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

        return new static($reversed);
    }

    /**
     * Squash and unify white characters into single space.
     *
     * @return Transformer
     */
    public function squashWhitechars()
    {
        return $this->regexReplace('/[[:space:]]+/', ' ')->strip();
    }

    /**
     * Insert given string at $idx.
     *
     * @param string      $substring
     * @param int         $idx
     * @param string|null $encoding
     *
     * @return Transformer
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

        return new static($start . $substring . $end);
    }

    /**
     * Prepend $substring if string doesn't begin with it.
     *
     * @param $substring
     * @param null $encoding
     *
     * @return Transformer
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
     * @return Transformer
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
            if (\mb_substr($this->string, -$len, null, $encoding) == $substring) {
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
     * @return Transformer
     */
    public function prefix($string)
    {
        return new static($string . $this->string);
    }

    /**
     * Append some string to the end.
     *
     * @param string $string
     *
     * @return Transformer
     */
    public function suffix($string)
    {
        return new static($this->string . $string);
    }

    /**
     * Adds PHP_EOL to the end of string.
     *
     * @return Transformer
     */
    public function eol()
    {
        return $this->suffix(PHP_EOL);
    }

    /**
     * Adds \r\n to the end of string.
     *
     * @return Transformer
     */
    public function eolrn()
    {
        return $this->suffix("\r\n");
    }

    /**
     * Adds \n to the end of string.
     *
     * @return Transformer
     */
    public function eoln()
    {
        return $this->suffix("\n");
    }

    public function __toString()
    {
        return (string) $this->string;
    }
}
