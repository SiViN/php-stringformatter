<?php

namespace m36\StringFormatter\Tests;


use m36\StringFormatter\FormatterNamed;

class FormatterNamedTransformerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	function replace()
	{
		$format = '{welcome} "{name:replace("world","John")}"';
		$fmt = new FormatterNamed($format);
		$res = $fmt->compile(array('welcome' => 'Hello', 'name' => 'world'));
		$this->assertEquals('Hello "John"', (string) $res);
	}

	/**
	 * @test
	 */
	function regexReplaceString()
	{
		$format = '{welcome} "{name:regexReplace("/(Jo)/","D$1n")}"';
		$fmt = new FormatterNamed($format);
		$res = $fmt->compile(array('welcome' => 'Hello', 'name' => 'Joe'));
		$this->assertEquals('Hello "DJone"', (string) $res);
	}

	/**
	 * @test
	 */
	function repeat()
	{
		$format = '{welcome}{name:repeat("3")}';
		$fmt = new FormatterNamed($format);
		$res = $fmt->compile(array('welcome' => 'Hello', 'name' => ' Joe'));
		$this->assertEquals('Hello Joe Joe Joe', (string) $res);
	}

	//TODO: add more Transformer method tests
}