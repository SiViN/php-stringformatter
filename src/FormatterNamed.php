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
     * @param array $params parameters used to format given string
     * @param bool  $merge  if true, params passed in constructor are merged with this given to FormatterNamed::compile
     *
     * @return TransformerBuilder
     */
    public function compile(array $params = null, $merge = true)
    {
        if (is_null($params)) {
            $params = $this->params;
        } elseif ($merge) {
            $params = \array_merge($this->params, $params);
        }

        $compiler = new Compiler($this->format, $params, Compiler::MODE_NAMED);

        return new TransformerBuilder($compiler);
    }

    public function __toString()
    {
        return $this->format;
    }
}
