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

namespace MSZ\String;

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
     * @param string $format format to parse
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
     * @param bool  $merge  if true, params passed in constructor are merged with this given to FormatterNamed::parse
     *
     * @return Transformer
     */
    public function parse(array $params = null, $merge = true)
    {
        if (is_null($params)) {
            $params = $this->params;
        }
        elseif ($merge) {
            $params = array_merge($this->params, $params);
        }

        $compiler = new Compiler($this->format, $params, Compiler::MODE_NAMED);
        $string = $compiler->run();

        return new Transformer($string);
    }
}
