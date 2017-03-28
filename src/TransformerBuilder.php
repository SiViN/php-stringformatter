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
    /** @var array */
    protected $modifiers = array();
    /** @var Compiler */
    protected $input;
    /** @var string */
    protected $unfolded;

    /**
     * TransformerBuilder constructor.
     *
     * @param Compiler $input
     */
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

    /**
     * @param string $name modifier name
     * @param array $args modifier arguments
     * @return TransformerBuilder
     */
    protected function addModifier($name, $args)
    {
        $modifiers = $this->modifiers;
        $modifiers[] = array('name' => $name, 'args' => $args);

        $ret = new static($this->getInput());
        $ret->modifiers = $modifiers;

        return $ret;
    }

    /**
     * Calls given callable from $fn. As a first argument is passed transformed string
     * from Transformer, then pass there other args from Transformer::transform method.
     *
     * @param mixed    $fn     callable in any format recognized by call_user_func_array
     * @param ...mixed $params params for $fn
     *
     * @return TransformerBuilder
     */
    public function transform()
    {
        return $this->addModifier('transform', \func_get_args());
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
     * @return TransformerBuilder
     */
    public function replace($from, $to)
    {
        return $this->addModifier('replace', array($from, $to));
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
     * @return TransformerBuilder
     */
    public function ireplace($from, $to)
    {
        return $this->addModifier('ireplace', array($from, $to));
    }

    /**
     * Wrapper for preg_replace or preg_replace_callback (depends on $replacement
     * being callback or not).
     *
     * @param string $pattern
     * @param string $replacement
     * @param int    $limit
     *
     * @return TransformerBuilder
     */
    public function regexReplace($pattern, $replacement, $limit = -1)
    {
        return $this->addModifier('regexReplace', array($pattern, $replacement, $limit));
    }

    /**
     * Wrapper for trim.
     *
     * @param string $charmask
     *
     * @return TransformerBuilder
     */
    public function strip($charmask = " \t\n\r\0\x0B")
    {
        return $this->addModifier('strip', array($charmask));
    }

    /**
     * Wrapper for ltrim.
     *
     * @param string $charmask
     *
     * @return TransformerBuilder
     */
    public function lstrip($charmask = " \t\n\r\0\x0B")
    {
        return $this->addModifier('lstrip', array($charmask));
    }

    /**
     * Wrapper for rtrim.
     *
     * @param string $charmask
     *
     * @return TransformerBuilder
     */
    public function rstrip($charmask = " \t\n\r\0\x0B")
    {
        return $this->addModifier('rstrip', array($charmask));
    }

    /**
     * Wrapper for strtoupper.
     *
     * @param string|null $encoding
     *
     * @return TransformerBuilder
     */
    public function upper($encoding = null)
    {
        return $this->addModifier('upper', array($encoding));
    }

    /**
     * Wrapper for strtolower.
     *
     * @param string|null $encoding
     *
     * @return TransformerBuilder
     */
    public function lower($encoding = null)
    {
        return $this->addModifier('lower', array($encoding));
    }

    /**
     * Wrapper for ucfirst.
     *
     * @param string|null $encoding
     *
     * @return TransformerBuilder
     */
    public function upperFirst($encoding = null)
    {
        return $this->addModifier('upperFirst', array($encoding));
    }

    /**
     * Wrapper for lcfirst.
     *
     * @param string|null $encoding
     *
     * @return TransformerBuilder
     */
    public function lowerFirst($encoding = null)
    {
        return $this->addModifier('lowerFirst', array($encoding));
    }

    /**
     * Wrapper for ucwords.
     *
     * @param string $delimiters (ignored if php do not handle this parameter)
     *
     * @return TransformerBuilder
     */
    public function upperWords($delimiters = null)
    {
        return $this->addModifier('upperWords', array($delimiters));
    }

    /**
     * Wrapper for wordwrap.
     *
     * @param int    $width
     * @param string $break
     * @param bool   $cut
     *
     * @return TransformerBuilder
     */
    public function wordWrap($width = 75, $break = "\n", $cut = false)
    {
        return $this->addModifier('wordWrap', array($width, $break, $cut));
    }

    /**
     * Wrapper for mb_substr / substr.
     *
     * @param $start
     * @param int|null $length
     * @param string|null $encoding
     *
     * @return TransformerBuilder
     */
    public function substr($start, $length = null, $encoding = null)
    {
        return $this->addModifier('substr', array($start, $length, $encoding));
    }

    /**
     * Wrapper for str_repeat.
     *
     * @param int $count
     *
     * @return TransformerBuilder
     */
    public function repeat($count)
    {
        return $this->addModifier('repeat', array($count));
    }

    /**
     * Reverse string.
     *
     * @param null $encoding
     *
     * @return TransformerBuilder
     */
    public function reverse($encoding = null)
    {
        return $this->addModifier('reverse', array($encoding));
    }

    /**
     * Squash and unify white characters into single space.
     *
     * @return TransformerBuilder
     */
    public function squashWhitechars()
    {
        return $this->addModifier('squashWhitechars', array());
    }

    /**
     * Insert given string at $idx.
     *
     * @param string      $substring
     * @param int         $idx
     * @param string|null $encoding
     *
     * @return TransformerBuilder
     */
    public function insert($substring, $idx, $encoding = null)
    {
        return $this->addModifier('insert', array($substring, $idx, $encoding));
    }

    /**
     * Prepend $substring if string doesn't begin with it.
     *
     * @param $substring
     * @param null $encoding
     *
     * @return TransformerBuilder
     */
    public function ensurePrefix($substring, $encoding = null)
    {
        return $this->addModifier('ensurePrefix', array($substring, $encoding));
    }

    /**
     * Append $substring if string doesn't end with it.
     *
     * @param $substring
     * @param null $encoding
     *
     * @return TransformerBuilder
     */
    public function ensureSuffix($substring, $encoding = null)
    {
        return $this->addModifier('ensureSuffix', array($substring, $encoding));
    }

    /**
     * Prepend some string on the beginning.
     *
     * @param string $string
     *
     * @return TransformerBuilder
     */
    public function prefix($string)
    {
        return $this->addModifier('prefix', array($string));
    }

    /**
     * Append some string to the end.
     *
     * @param string $string
     *
     * @return TransformerBuilder
     */
    public function suffix($string)
    {
        return $this->addModifier('suffix', array($string));
    }

    /**
     * Append some string to the beginning and to the end.
     *
     * @param string $string
     *
     * @return TransformerBuilder
     */
    public function surround($string)
    {
        return $this->addModifier('surround', array($string));
    }

    /**
     * Adds PHP_EOL to the end of string.
     *
     * @return TransformerBuilder
     */
    public function eol()
    {
        return $this->addModifier('eol', array());
    }

    /**
     * Adds \r\n to the end of string.
     *
     * @return TransformerBuilder
     */
    public function eolrn()
    {
        return $this->addModifier('eolrn', array());
    }

    /**
     * Adds \n to the end of string.
     *
     * @return TransformerBuilder
     */
    public function eoln()
    {
        return $this->addModifier('eoln', array());
    }

    /**
     * Apply all modifiers for given input and return formatted string.
     *
     * @return string
     */
    public function unfold()
    {
        if (is_null($this->unfolded)) {
            $string = $this->input->run();
            $transformer = new Transformer($string);
            foreach ($this->modifiers as $modifier) {
                $transformer = call_user_func_array(array($transformer, $modifier['name']), $modifier['args']);
            }
            $this->unfolded = (string) $transformer;
        }

        return $this->unfolded;
    }

    public function __toString()
    {
        return $this->unfold();
    }
}
