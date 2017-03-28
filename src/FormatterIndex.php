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
     * @return TransformerBuilder
     */
    public function compile()
    {
        $params = (\func_num_args() > 0 ? \func_get_args() : $this->params);

        $compiler = new Compiler($this->format, $params, Compiler::MODE_INDEX);

        return new TransformerBuilder($compiler);
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
     * @return TransformerBuilder
     */
    public function compileIformat()
    {
        $params = (\func_num_args() > 0 ? \func_get_args() : $this->params);

        $compiler = new Compiler($this->format, $params, Compiler::MODE_INDEX);

        return new TransformerBuilder($compiler);
    }

    public function __toString()
    {
        return $this->format;
    }
}
