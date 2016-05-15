<?php

namespace MSZ\String\Tests;

use \MSZ\String\FormatterIndex;

class FormatterIndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function simple()
    {
        $format = '{} {}!';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse('Hello', 'world');
        $this->assertEquals('Hello world!', (string)$res);
    }

    /**
     * @test
     */
    public function simpleWithOrder()
    {
        $format = '{1} {0}!';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse('Hello', 'world');
        $this->assertEquals('world Hello!', (string)$res);
    }

    /**
     * @test
     */
    public function alignLeft()
    {
        $format = '{} "{1:<20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse('Hello', 'world');
        $this->assertEquals('Hello "world               "', (string)$res);
    }

    /**
     * @test
     */
    public function alignRight()
    {
        $format = '{} "{1:>20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse('Hello', 'world');
        $this->assertEquals('Hello "               world"', (string)$res);
    }

    /**
     * @test
     */
    public function alignCenter()
    {
        $format = '{} "{1:^20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse('Hello', 'world');
        $this->assertEquals('Hello "       world        "', (string)$res);
    }

    /**
     * @test
     */
    public function alignCenterWithCharacter()
    {
        $format = '{} "{1:*^20}"';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse('Hello', 'world');
        $this->assertEquals('Hello "*******world********"', (string)$res);
    }

    /**
     * @test
     */
    public function sprintfLike()
    {
        $format = 'Test: {%%.3f}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse(2.1234567);
        $this->assertEquals('Test: 2.123', (string)$res);

        $format = 'Test 2: {%%c}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse(97);
        $this->assertEquals('Test 2: a', (string)$res);
    }

    /**
     * @test
     */
    public function objectProperty()
    {
        $format = 'Test: {->property}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse(new TestIndexStringFormatter());
        $this->assertEquals('Test: test property', (string)$res);
    }

    /**
     * @test
     */
    public function objectMethod()
    {
        $format = 'Test: {->method}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse(new TestIndexStringFormatter());
        $this->assertEquals('Test: test method', (string)$res);
    }

    /**
     * @test
     */
    public function convertInteger()
    {
        $format = 'Test: 10: {#d}, 16: {0#x}, 2: {0#b}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse(11);
        $this->assertEquals('Test: 10: 11, 16: b, 2: 1011', (string)$res);

        $format = 'Test: 10: {#10}, 16: {0#16}, 2: {0#2}, 7: {0#7}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse(11);
        $this->assertEquals('Test: 10: 11, 16: b, 2: 1011, 7: 14', (string)$res);
    }

    /**
     * @test
     */
    public function arrayIndex()
    {
        $format = 'Test: test1: {[test1]}, test2: {0[test2]}';
        $fmt = new FormatterIndex($format);
        $res = $fmt->parse(array('test1' => 'Hello', 'test2' => 'world'));
        $this->assertEquals('Test: test1: Hello, test2: world', (string)$res);
    }

    /**
     * @test
     */
    public function paramsInConstructor()
    {
        $format = '{} {}!';
        $fmt = new FormatterIndex($format, 'Hello', 'world');
        $res = $fmt->parse();
        $this->assertEquals('Hello world!', (string)$res);

        $format = '{} {}!';
        $fmt = new FormatterIndex($format, 'Hello', 'world');
        $res = $fmt->parse('Hi', 'earth');
        $this->assertEquals('Hi earth!', (string)$res);
    }
}

class TestIndexStringFormatter {
    public $property = 'test property';
    public function method() {
        return 'test method';
    }
}
