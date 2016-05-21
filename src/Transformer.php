<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <http://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.5.4
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
        $args = func_get_args();
        $fn = array_shift($args);
        array_unshift($args, $this->string);

        return new static(call_user_func_array($fn, $args));
    }

    /**
     * Wrapper for str_replace.
     *
     * @param string $from
     * @param string $to
     *
     * @return Transformer
     */
    public function replace($from, $to)
    {
        return new static(str_replace($from, $to, $this->string));
    }

    /**
     * Wrapper for str_ireplace.
     *
     * @param string $from
     * @param string $to
     *
     * @return Transformer
     */
    public function ireplace($from, $to)
    {
        return new static(str_ireplace($from, $to, $this->string));
    }

    /**
     * Wrapper for preg_replace or preg_replace_callback (depends on $replacement
     * being .callback or not).
     *
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     *
     * @return Transformer
     */
    public function regexReplace($pattern, $replacement, $limit = -1)
    {
        if (is_callable($replacement)) {
            $rxp_function = 'preg_replace_callback';
        } else {
            $rxp_function = 'preg_replace';
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
        return $this->transform('trim', $charmask);
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
        return $this->transform('ltrim', $charmask);
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
        return $this->transform('rtrim', $charmask);
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
        if (function_exists('mb_convert_case')) {
            if (is_null($encoding)) {
                $encoding = mb_internal_encoding();
            }
            return $this->transform('mb_convert_case', MB_CASE_UPPER, $encoding);
        }
        else {
            return $this->transform('strtoupper');
        }
    }

    /**
     * Wrapper for strtolower.
     *
     * @param string|null $encoding
     * @return Transformer
     */
    public function lower($encoding = null)
    {
        if (function_exists('mb_convert_case')) {
            if (is_null($encoding)) {
                $encoding = mb_internal_encoding();
            }
            return $this->transform('mb_convert_case', MB_CASE_LOWER, $encoding);
        }
        else {
            return $this->transform('strtolower');
        }
    }

    /**
     * Wrapper for ucfirst.
     *
     * @param string|null $encoding
     * @return Transformer
     */
    public function upperFirst($encoding = null)
    {
        if (function_exists('mb_strtoupper')) {
            if (is_null($encoding)) {
                $encoding = mb_internal_encoding();
            }

            $string = mb_strtoupper(mb_substr($this->string, 0, 1, $encoding), $encoding);
            return new static($string . mb_substr($this->string, 1, null, $encoding));
        }
        else {
            return $this->transform('ucfirst');
        }
    }

    /**
     * Wrapper for lcfirst.
     *
     * @param string|null $encoding
     * @return Transformer
     */
    public function lowerFirst($encoding = null)
    {
        if (function_exists('mb_strtolower')) {
            if (is_null($encoding)) {
                $encoding = mb_internal_encoding();
            }

            $string = mb_strtolower(mb_substr($this->string, 0, 1, $encoding), $encoding);
            return new static($string . mb_substr($this->string, 1, null, $encoding));
        }
        else {
            return $this->transform('lcfirst');
        }
    }

    /**
     * Wrapper for ucwords.
     *
     * @param string $delimiters (ignored if php do not handle this parameter)
     *
     * @return Transformer
     */
    public function upperWords($delimiters = " \t\r\n\f\v")
    {
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
        return $this->transform('wordwrap', $width, $break, $cut);
    }

    /**
     * Wrapper for mb_substr / substr.
     *
     * @param $start
     * @param int|null $length
     *
     * @return Transformer
     */
    public function substr($start, $length = null, $encoding = null)
    {
        if (function_exists('mb_substr')) {
            if (is_null($encoding)) {
                $encoding = mb_internal_encoding();
            }
            return $this->transform('mb_substr', $start, $length, $encoding);
        } else {
            return $this->transform('substr', $start, $length);
        }
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
