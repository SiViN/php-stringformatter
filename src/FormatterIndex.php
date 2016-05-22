<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <http://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.6.0
 */

namespace m36\StringFormatter;

class FormatterIndex implements IFormatter
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
    const TRACE_LEVEL_FUNCTIONAL = 5;

    /**
     * @param string   $format format to compile
     * @param ...mixed $params parameters used to format given string
     */
    public function __construct($format)
    {
        $this->format = $format;

        if (\func_num_args() > 1) {
            $args = \func_get_args();
            \array_shift($args);
            $this->params = $args;
        }
    }

    /**
     * Parse given format and fill it's placeholders with params.
     *
     * Should be always synchronized with FormatterIndex::compileIformat
     *
     * @param ...mixed $params parameters used to format given string
     *
     * @return Transformer
     */
    public function compile()
    {
        $params = (\func_num_args() > 0 ? \func_get_args() : $this->params);

        $compiler = new Compiler($this->format, $params, Compiler::MODE_INDEX, static::TRACE_LEVEL_NORMAL);
        $string = $compiler->run();

        return new Transformer($string);
    }

    /**
     * Parse given format and fill it's placeholders with params.
     * Should be used only for internal use, can be changed anytime without
     * warning.
     *
     * Should be always synchronized with FormatterIndex::compile
     *
     * @param ...mixed $params parameters used to format given string
     *
     * @internal
     *
     * @return Transformer
     */
    public function compileIformat()
    {
        $params = (\func_num_args() > 0 ? \func_get_args() : $this->params);

        $compiler = new Compiler($this->format, $params, Compiler::MODE_INDEX, static::TRACE_LEVEL_FUNCTIONAL);
        $string = $compiler->run();

        return new Transformer($string);
    }

    public function __toString()
    {
        return $this->format;
    }
}
