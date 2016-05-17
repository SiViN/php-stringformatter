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

namespace Msztolcman\StringFormatter\Tests;

use Msztolcman\StringFormatter\FormatterIndex;
use Msztolcman\StringFormatter;

class FormatterIndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function simple()
    {
        $format = '{} {}!';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile('Hello', 'world');
        $this->assertEquals('Hello world!', (string) $res);
    }

    /**
     * @test
     */
    public function simpleWithOrder()
    {
        $format = '{1} {0}!';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile('Hello', 'world');
        $this->assertEquals('world Hello!', (string) $res);
    }

    /**
     * @test
     */
    public function alignLeft()
    {
        $format = '{} "{1:<20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile('Hello', 'world');
        $this->assertEquals('Hello "world               "', (string) $res);
    }

    /**
     * @test
     */
    public function alignRight()
    {
        $format = '{} "{1:>20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile('Hello', 'world');
        $this->assertEquals('Hello "               world"', (string) $res);
    }

    /**
     * @test
     */
    public function alignCenter()
    {
        $format = '{} "{1:^20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile('Hello', 'world');
        $this->assertEquals('Hello "       world        "', (string) $res);
    }

    /**
     * @test
     */
    public function alignCenterWithCharacter()
    {
        $format = '{} "{1:*^20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile('Hello', 'world');
        $this->assertEquals('Hello "*******world********"', (string) $res);
    }

    /**
     * @test
     */
    public function sprintfLike()
    {
        $format = 'Test: {%%.3f}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(2.1234567);
        $this->assertEquals('Test: 2.123', (string) $res);

        $format = 'Test 2: {%%c}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(97);
        $this->assertEquals('Test 2: a', (string) $res);
    }

    /**
     * @test
     */
    public function objectProperty()
    {
        $format = 'Test: {->property}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(new TestIndexStringFormatter());
        $this->assertEquals('Test: test property', (string) $res);
    }

    /**
     * @test
     */
    public function objectMethod()
    {
        $format = 'Test: {->method}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(new TestIndexStringFormatter());
        $this->assertEquals('Test: test method', (string) $res);
    }

    /**
     * @test
     */
    public function convertInteger()
    {
        $format = 'Test: 10: {#d}, 16: {0#x}, 2: {0#b}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(11);
        $this->assertEquals('Test: 10: 11, 16: b, 2: 1011', (string) $res);

        $format = 'Test: 10: {#10}, 16: {0#16}, 2: {0#2}, 7: {0#7}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(11);
        $this->assertEquals('Test: 10: 11, 16: b, 2: 1011, 7: 14', (string) $res);
    }

    /**
     * @test
     */
    public function arrayIndex()
    {
        $format = 'Test: test1: {[test1]}, test2: {0[test2]}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(array('test1' => 'Hello', 'test2' => 'world'));
        $this->assertEquals('Test: test1: Hello, test2: world', (string) $res);
    }

    /**
     * @test
     */
    public function paramsInConstructor()
    {
        $format = '{} {}!';
        $fmt = new FormatterIndex($format, 'Hello', 'world');
        $res = $fmt->compile();
        $this->assertEquals('Hello world!', (string) $res);

        $format = '{} {}!';
        $fmt = new FormatterIndex($format, 'Hello', 'world');
        $res = $fmt->compile('Hi', 'earth');
        $this->assertEquals('Hi earth!', (string) $res);
    }

    /**
     * @test
     */
    public function functionalCall()
    {
        $format = 'Some {} method{}';
        $adj = 'glorious';
        $char = '!';
        $expected = "Some {$adj} method{$char}";

        $res = StringFormatter\iformat($format, [$adj, $char]);
        $this->assertEquals($expected, (string) $res);

        $res = StringFormatter\iformatl($format, $adj, $char);
        $this->assertEquals($expected, (string) $res);
    }
}

class TestIndexStringFormatter
{
    public $property = 'test property';

    public function method()
    {
        return 'test method';
    }
}
