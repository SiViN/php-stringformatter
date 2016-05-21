<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <http://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.5.1
 */

namespace m36\StringFormatter;

class FormatterNamed implements IFormatter
{
    /**
     * Store provided by user format string.
     *
     * @var string
     */
    protected $format = null;

    /**
     * Params for placeholders from format.
     *
     * @var array
     */
    protected $params = array();

    /**
     * Trace level for OO API - internal use.
     */
    const TRACE_LEVEL_NORMAL = 3;

    /**
     * Trace level for functional API - internal use.
     */
    const TRACE_LEVEL_FUNCTIONAL = 4;

    /**
     * @param string $format format to compile
     * @param array  $params parameters used to format given string
     */
    public function __construct($format, $params = array())
    {
        $this->format = $format;
        $this->params = $params;
    }

    /**
     * Parse given format and fill it's placeholders with params.
     *
     * Should be always synchronized with FormatterNamed::compileIformat
     *
     * @param array $params parameters used to format given string
     * @param bool  $merge  if true, params passed in constructor are merged with this given to FormatterNamed::compile
     *
     * @return Transformer
     */
    public function compile(array $params = null, $merge = true)
    {
        if (is_null($params)) {
            $params = $this->params;
        } elseif ($merge) {
            $params = array_merge($this->params, $params);
        }

        $compiler = new Compiler($this->format, $params, Compiler::MODE_NAMED, static::TRACE_LEVEL_NORMAL);
        $string = $compiler->run();

        return new Transformer($string);
    }

    /**
     * Parse given format and fill it's placeholders with params.
     * Should be used only for internal use, can be changed anytime without
     * warning.
     *
     * Should be always synchronized with FormatterNamed::compile
     *
     * @param array $params parameters used to format given string
     * @param bool  $merge  if true, params passed in constructor are merged with this given to FormatterNamed::compile
     *
     * @internal
     *
     * @return Transformer
     */
    public function compileNformat(array $params = null, $merge = true)
    {
        if (is_null($params)) {
            $params = $this->params;
        } elseif ($merge) {
            $params = array_merge($this->params, $params);
        }

        $compiler = new Compiler($this->format, $params, Compiler::MODE_NAMED, static::TRACE_LEVEL_FUNCTIONAL);
        $string = $compiler->run();

        return new Transformer($string);
    }

    public function __toString()
    {
        return $this->format;
    }
}
