<?php

/*
 * This file is part of msztolcman/stringformatter.
 *
 * (c) Marcin Sztolcman <http://urzenia.net>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.4.0
 */

namespace MSZ\String;

interface IFormatter {
    /**
     * Parse given format
     * @return Transformer
     */
    public function parse();
}
