<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <http://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.5.4
 */

namespace m36\StringFormatter;

/**
 * Functional API for FormatterIndex. Instead of:
 *
 *   new FormatterIndex('some {} format')->compile('glorious');
 *
 * you can call:
 *
 *   iformat('some {} format', ['glorious']);
 *
 * Few characters less :)
 *
 * @param string $format
 * @param array  $params parameters used to fill placeholders
 *
 * @return Transformer
 */
function iformat($format, $params = array())
{
    $fmt = new FormatterIndex($format);

    return call_user_func_array(array($fmt, 'compileIformat'), $params);
}

/**
 * Functional API for FormatterIndex. Instead of:
 *
 *   new FormatterIndex('some {} format')->compile('glorious');
 *
 * you can call:
 *
 *   iformatl('some {} format', 'glorious');
 *
 * Few characters less :)
 *
 * @param string $format
 * @param array  $params parameters used to fill placeholders
 *
 * @return Transformer
 */
function iformatl($format)
{
    $params = func_get_args();
    array_shift($params);

    $fmt = new FormatterIndex($format);

    return call_user_func_array(array($fmt, 'compileIformat'), $params);
}

/**
 * Functional API for FormatterNamed. Instead of:
 *
 *   new FormatterNamed('some {adjective} format')->compile(['adjective' => 'glorious']);
 *
 * you can call:
 *
 *   nformat('some {adjective} format', ['adjective' => 'glorious']);
 *
 * Few characters less :)
 *
 * @param string $format
 * @param array  $params parameters used to fill placeholders
 *
 * @return Transformer
 */
function nformat($format, $params = array())
{
    $fmt = new FormatterNamed($format);

    return $fmt->compileNformat($params);
}
