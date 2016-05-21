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

interface IFormatter
{
    /**
     * Parse given format.
     *
     * @return Transformer
     */
    public function compile();
}
