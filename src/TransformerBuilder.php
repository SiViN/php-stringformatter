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

class TransformerBuilder
{
    /** @var array  */
    protected $modifiers = array();
    /** @var Compiler  */
    protected $input;

    public function __construct(Compiler $input)
    {
        $this->input = $input;
    }

    /**
     * @return Compiler
     */
    public function getInput()
    {
        return $this->input;
    }

    public function addModifier($name, $args)
    {
        $this->modifiers[] = array('name' => $name, 'args' => $args);
    }

    /**
     * Calls given callable from $fn. As a first argument is passed transformed string
     * from Transformer, then pass there other args from Transformer::transform method.
     *
     * @param mixed    $fn     callable in any format recognized by call_user_func_array
     * @param ...mixed $params params for $fn
     *
     * @return $this
     */
    public function transform()
    {
        $args = \func_get_args();
        $this->addModifier('transform', $args);
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
     * @return $this
     */
    public function replace($from, $to)
    {
        $this->addModifier('replace', array($from, $to));
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
     * @return $this
     */
    public function ireplace($from, $to)
    {
        $this->addModifier('ireplace', array($from, $to));
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
     * @return $this
     */
    public function regexReplace($pattern, $replacement, $limit = -1)
    {
        $this->addModifier('regexReplace', array($pattern, $replacement, $limit));
        return $this;
    }

    /**
     * Wrapper for trim.
     *
     * @param string $charmask
     *
     * @return $this
     */
    public function strip($charmask = " \t\n\r\0\x0B")
    {
        $this->addModifier('strip', array($charmask));
        return $this;
    }

    /**
     * Wrapper for ltrim.
     *
     * @param string $charmask
     *
     * @return $this
     */
    public function lstrip($charmask = " \t\n\r\0\x0B")
    {
        $this->addModifier('lstrip', array($charmask));
        return $this;
    }

    /**
     * Wrapper for rtrim.
     *
     * @param string $charmask
     *
     * @return $this
     */
    public function rstrip($charmask = " \t\n\r\0\x0B")
    {
        $this->addModifier('rstrip', array($charmask));
        return $this;
    }

    /**
     * Wrapper for strtoupper.
     *
     * @param string|null $encoding
     *
     * @return $this
     */
    public function upper($encoding = null)
    {
        $this->addModifier('upper', array($encoding));
        return $this;
    }

    /**
     * Wrapper for strtolower.
     *
     * @param string|null $encoding
     *
     * @return $this
     */
    public function lower($encoding = null)
    {
        $this->addModifier('lower', array($encoding));
        return $this;
    }

    /**
     * Wrapper for ucfirst.
     *
     * @param string|null $encoding
     *
     * @return $this
     */
    public function upperFirst($encoding = null)
    {
        $this->addModifier('upperFirst', array($encoding));
        return $this;
    }

    /**
     * Wrapper for lcfirst.
     *
     * @param string|null $encoding
     *
     * @return $this
     */
    public function lowerFirst($encoding = null)
    {
        $this->addModifier('lowerFirst', array($encoding));
        return $this;
    }

    /**
     * Wrapper for ucwords.
     *
     * @param string $delimiters (ignored if php do not handle this parameter)
     *
     * @return $this
     */
    public function upperWords($delimiters = null)
    {
        $this->addModifier('upperWords', array($delimiters));
        return $this;
    }

    /**
     * Wrapper for wordwrap.
     *
     * @param int    $width
     * @param string $break
     * @param bool   $cut
     *
     * @return $this
     */
    public function wordWrap($width = 75, $break = "\n", $cut = false)
    {
        $this->addModifier('wordWrap', array($width, $break, $cut));
        return $this;
    }

    /**
     * Wrapper for mb_substr / substr.
     *
     * @param $start
     * @param int|null $length
     * @param string|null $encoding
     *
     * @return $this
     */
    public function substr($start, $length = null, $encoding = null)
    {
        $this->addModifier('substr', array($start, $length, $encoding));
        return $this;
    }

    /**
     * Wrapper for str_repeat.
     *
     * @param int $count
     *
     * @return $this
     */
    public function repeat($count)
    {
        $this->addModifier('repeat', array($count));
        return $this;
    }

    /**
     * Reverse string.
     *
     * @param null $encoding
     *
     * @return $this
     */
    public function reverse($encoding = null)
    {
        $this->addModifier('reverse', array($encoding));
        return $this;
    }

    /**
     * Squash and unify white characters into single space.
     *
     * @return $this
     */
    public function squashWhitechars()
    {
        $this->addModifier('squashWhitechars', array());
        return $this;
    }

    /**
     * Insert given string at $idx.
     *
     * @param string      $substring
     * @param int         $idx
     * @param string|null $encoding
     *
     * @return $this
     */
    public function insert($substring, $idx, $encoding = null)
    {
        $this->addModifier('insert', array($substring, $idx, $encoding));
        return $this;
    }

    /**
     * Prepend $substring if string doesn't begin with it.
     *
     * @param $substring
     * @param null $encoding
     *
     * @return $this
     */
    public function ensurePrefix($substring, $encoding = null)
    {
        $this->addModifier('ensurePrefix', array($substring, $encoding));
        return $this;
    }

    /**
     * Append $substring if string doesn't end with it.
     *
     * @param $substring
     * @param null $encoding
     *
     * @return $this
     */
    public function ensureSuffix($substring, $encoding = null)
    {
        $this->addModifier('ensureSuffix', array($substring, $encoding));
        return $this;
    }

    /**
     * Prepend some string on the beginning.
     *
     * @param string $string
     *
     * @return $this
     */
    public function prefix($string)
    {
        $this->addModifier('prefix', array($string));
        return $this;
    }

    /**
     * Append some string to the end.
     *
     * @param string $string
     *
     * @return $this
     */
    public function suffix($string)
    {
        $this->addModifier('suffix', array($string));
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
        $this->addModifier('surround', array($string));
        return $this;
    }

    /**
     * Adds PHP_EOL to the end of string.
     *
     * @return $this
     */
    public function eol()
    {
        $this->addModifier('eol', array());
        return $this;
    }

    /**
     * Adds \r\n to the end of string.
     *
     * @return $this
     */
    public function eolrn()
    {
        $this->addModifier('eolrn', array());
        return $this;
    }

    /**
     * Adds \n to the end of string.
     *
     * @return $this
     */
    public function eoln()
    {
        $this->addModifier('eoln', array());
        return $this;
    }

    public function unfold()
    {
        $string = $this->input->run();
        $transformer = new Transformer($string);
        foreach ($this->modifiers as $modifier) {
            $transformer = call_user_func_array(array($transformer, $modifier['name']), $modifier['args']);
        }
        return (string) $transformer;
    }

    public function __toString()
    {
        return $this->unfold();
    }
}
