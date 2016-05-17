<?php

/*
 * This file is part of msztolcman/stringformatter.
 *
 * (c) Marcin Sztolcman <http://urzenia.net>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.5.0
 */

namespace Msztolcman\StringFormatter;

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
     * @return Transformer
     */
    public function upper()
    {
        return $this->transform('strtoupper');
    }

    /**
     * Wrapper for strtolower.
     *
     * @return Transformer
     */
    public function lower()
    {
        return $this->transform('strtolower');
    }

    /**
     * Wrapper for ucfirst.
     *
     * @return Transformer
     */
    public function upperFirst()
    {
        return $this->transform('ucfirst');
    }

    /**
     * Wrapper for lcfirst.
     *
     * @return Transformer
     */
    public function lowerFirst()
    {
        return $this->transform('lcfirst');
    }

    /**
     * Wrapper for ucwords.
     *
     * @param string $delimiters
     *
     * @return Transformer
     */
    public function upperWords($delimiters = " \t\r\n\f\v")
    {
        return $this->transform('ucwords', $delimiters);
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
     * Wrapper for substr.
     *
     * @param $start
     * @param int|null $length
     *
     * @return Transformer
     */
    public function substr($start, $length = null)
    {
        return $this->transform('substr', $start, $length);
    }

    /**
     * Adds PHP_EOL to the end of string.
     *
     * @return Transformer
     */
    public function eol()
    {
        return new static($this->string . PHP_EOL);
    }

    /**
     * Adds \r\n to the end of string.
     *
     * @return Transformer
     */
    public function eolrn()
    {
        return new static($this->string . "\r\n");
    }

    /**
     * Adds \n to the end of string.
     *
     * @return Transformer
     */
    public function eoln()
    {
        return new static($this->string . "\n");
    }

    public function __toString()
    {
        return (string) $this->string;
    }
}
