<?php

namespace Msztolcman\StringFormatter;

/**
 * Other way to use FormatterIndex. Instead of:
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
 * @param array $params parameters used to fill placeholders
 *
 * @return Transformer
 */
function iformat($format, $params=array())
{
    $fmt = new FormatterIndex($format);
    return call_user_func_array(array($fmt, 'compile'), $params);
}

/**
 * Other way to use FormatterIndex. Instead of:
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
 * @param array $params parameters used to fill placeholders
 *
 * @return Transformer
 */
function iformatl($format)
{
    $params = func_get_args();
    array_shift($params);
    return iformat($format, $params);
}

/**
 * Other way to use FormatterNamed. Instead of:
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
 * @param array $params parameters used to fill placeholders
 *
 * @return Transformer
 */
function nformat($format, $params=array())
{
    $fmt = new FormatterNamed($format);
    return $fmt->compile($params);
}
