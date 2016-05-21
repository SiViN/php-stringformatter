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

namespace m36\StringFormatter\Tests;

use m36\StringFormatter;
use m36\StringFormatter\FormatterNamed;

class FormatterNamedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function simple()
    {
        $format = '{welcome} {name}!';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('welcome' => 'Hello', 'name' => 'world'));
        $this->assertEquals('Hello world!', (string) $res);
    }

    /**
     * @test
     */
    public function alignLeft()
    {
        $format = '{welcome} "{name:<20}"';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('welcome' => 'Hello', 'name' => 'world'));
        $this->assertEquals('Hello "world               "', (string) $res);
    }

    /**
     * @test
     */
    public function alignRight()
    {
        $format = '{welcome} "{name:>20}"';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('welcome' => 'Hello', 'name' => 'world'));
        $this->assertEquals('Hello "               world"', (string) $res);
    }

    /**
     * @test
     */
    public function alignCenter()
    {
        $format = '{welcome} "{name:^20}"';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('welcome' => 'Hello', 'name' => 'world'));
        $this->assertEquals('Hello "       world        "', (string) $res);
    }

    /**
     * @test
     */
    public function alignCenterWithCharacter()
    {
        $format = '{welcome} "{name:*^20}"';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('welcome' => 'Hello', 'name' => 'world'));
        $this->assertEquals('Hello "*******world********"', (string) $res);
    }

    /**
     * @test
     */
    public function sprintfLike()
    {
        $format = 'Test: {data%%.3f}';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('data' => 2.1234567));
        $this->assertEquals('Test: 2.123', (string) $res);

        $format = 'Test 2: {data%%c}';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('data' => 97));
        $this->assertEquals('Test 2: a', (string) $res);
    }

    /**
     * @test
     */
    public function objectProperty()
    {
        $format = 'Test: {data->property}';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('data' => new TestNamedStringFormatter()));
        $this->assertEquals('Test: test property', (string) $res);
    }

    /**
     * @test
     */
    public function objectMethod()
    {
        $format = 'Test: {data->method}';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('data' => new TestNamedStringFormatter()));
        $this->assertEquals('Test: test method', (string) $res);
    }

    /**
     * @test
     */
    public function convertInteger()
    {
        $format = 'Test: 10: {data#d}, 16: {data#x}, 2: {data#b}';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('data' => 11));
        $this->assertEquals('Test: 10: 11, 16: b, 2: 1011', (string) $res);

        $format = 'Test: 10: {data#10}, 16: {data#16}, 2: {data#2}, 7: {data#7}';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('data' => 11));
        $this->assertEquals('Test: 10: 11, 16: b, 2: 1011, 7: 14', (string) $res);
    }

    /**
     * @test
     */
    public function arrayIndex()
    {
        $format = 'Test: test1: {data[test1]}, test2: {data[test2]}';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(array('data' => array('test1' => 'Hello', 'test2' => 'world')));
        $this->assertEquals('Test: test1: Hello, test2: world', (string) $res);
    }

    /**
     * @test
     */
    public function paramsInConstructor()
    {
        $format = '{welcome} {name}!';
        $fmt = new FormatterNamed($format, array('welcome' => 'Hello', 'name' => 'world'));
        $res = $fmt->compile();
        $this->assertEquals('Hello world!', (string) $res);

        $format = '{welcome} {name}!';
        $fmt = new FormatterNamed($format, array('welcome' => 'Hello', 'name' => 'world'));
        $res = $fmt->compile(array('name' => 'earth'));
        $this->assertEquals('Hello earth!', (string) $res);

        $format = '{welcome} {name}!';
        $fmt = new FormatterNamed($format, array('welcome' => 'Hello', 'name' => 'world'));
        $res = $fmt->compile(array('welcome' => 'Hi', 'name' => 'earth'));
        $this->assertEquals('Hi earth!', (string) $res);
    }

    /**
     * @test
     */
    public function functionalCall()
    {
        $format = 'Some {adj} method{char}';
        $adj = 'glorious';
        $char = '!';
        $expected = "Some {$adj} method{$char}";

        $res = StringFormatter\nformat($format, array('adj' => $adj, 'char' => $char));
        $this->assertEquals($expected, (string) $res);
    }
}

class TestNamedStringFormatter
{
    public $property = 'test property';

    public function method()
    {
        return 'test method';
    }
}
