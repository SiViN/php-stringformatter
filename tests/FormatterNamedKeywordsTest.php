<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <http://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.5.2
 */

namespace m36\StringFormatter\Tests;

use m36\StringFormatter;
use m36\StringFormatter\FormatterNamed;

class FormatterNamedKeywordsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function keywordClass()
    {
        $format = 'Test {@class} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test FormatterNamedKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordClassNFormat()
    {
        $format = 'Test {@class} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test FormatterNamedKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordClassLong()
    {
        $format = 'Test {@classLong} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterNamedKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordClassLongNFormat()
    {
        $format = 'Test {@classLong} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterNamedKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethod()
    {
        $format = 'Test {@method} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test FormatterNamedKeywordsTest::keywordMethod Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethodNFormat()
    {
        $format = 'Test {@method} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test FormatterNamedKeywordsTest::keywordMethodNFormat Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethodLong()
    {
        $format = 'Test {@methodLong} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterNamedKeywordsTest::keywordMethodLong Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethodLongNFormat()
    {
        $format = 'Test {@methodLong} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterNamedKeywordsTest::keywordMethodLongNFormat Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFunction()
    {
        $format = 'Test {@function} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test keywordFunction Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFunctionNFormat()
    {
        $format = 'Test {@function} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test keywordFunctionNFormat Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFile()
    {
        $format = 'Test {@file} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test FormatterNamedKeywordsTest.php Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFileNFormat()
    {
        $format = 'Test {@file} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test FormatterNamedKeywordsTest.php Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFileLong()
    {
        $format = 'Test {@fileLong} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test ' . __FILE__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFileLongNFormat()
    {
        $format = 'Test {@fileLong} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test ' . __FILE__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDir()
    {
        $format = 'Test {@dir} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test tests Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDirNFormat()
    {
        $format = 'Test {@dir} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test tests Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDirLong()
    {
        $format = 'Test {@dirLong} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test ' . __DIR__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDirLongNFormat()
    {
        $format = 'Test {@dirLong} Test';
        $res = StringFormatter\nformat($format);
        $this->assertEquals('Test ' . __DIR__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordLine()
    {
        $format = 'Test {@line} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(); $line = __LINE__;
        $this->assertEquals('Test ' . $line . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordLineNFormat()
    {
        $format = 'Test {@line} Test';
        $res = StringFormatter\nformat($format); $line = __LINE__;
        $this->assertEquals('Test ' . $line . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function deeplyNested()
    {
        $this->assertEquals('Test FormatterNamedKeywordsTest::nested4 Test', (string) $this->nested1());
    }

    protected function nested1()
    {
        return $this->nested2();
    }

    protected function nested2()
    {
        return $this->nested3();
    }

    protected function nested3()
    {
        return $this->nested4();
    }

    protected function nested4()
    {
        $format = 'Test {@method} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();

        return $res;
    }

    /**
     * @test
     */
    public function deeplyNestedNFormat()
    {
        $this->assertEquals('Test FormatterNamedKeywordsTest::nested4NFormat Test', (string) $this->nested1NFormat());
    }

    protected function nested1NFormat()
    {
        return $this->nested2NFormat();
    }

    protected function nested2NFormat()
    {
        return $this->nested3NFormat();
    }

    protected function nested3NFormat()
    {
        return $this->nested4NFormat();
    }

    protected function nested4NFormat()
    {
        $format = 'Test {@method} Test';
        $res = StringFormatter\nformat($format);

        return $res;
    }

    /**
     * @test
     */
    public function combined()
    {
        $format = 'Test {@dir}:{@file}:{@line}:{@method} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterNamedKeywordsTest.php:{$line}:" .
            'FormatterNamedKeywordsTest::combined Test', (string) $res);
    }

    /**
     * @test
     */
    public function combinedNFormat()
    {
        $format = 'Test {@dir}:{@file}:{@line}:{@method} Test';
        $res = StringFormatter\nformat($format); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterNamedKeywordsTest.php:{$line}:" .
            'FormatterNamedKeywordsTest::combinedNFormat Test', (string) $res);
    }
}
