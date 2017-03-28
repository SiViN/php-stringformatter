<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <https://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.6.0
 */

namespace m36\StringFormatter\Tests;

use m36\StringFormatter;
use m36\StringFormatter\FormatterIndex;

class FormatterIndexKeywordsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function keywordClass()
    {
        $format = 'Test {@class} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test FormatterIndexKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordClassIFormat()
    {
        $format = 'Test {@class} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test FormatterIndexKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordClassLong()
    {
        $format = 'Test {@classLong} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterIndexKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordClassLongIFormat()
    {
        $format = 'Test {@classLong} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterIndexKeywordsTest Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethod()
    {
        $format = 'Test {@method} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test FormatterIndexKeywordsTest::keywordMethod Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethodIFormat()
    {
        $format = 'Test {@method} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test FormatterIndexKeywordsTest::keywordMethodIFormat Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethodLong()
    {
        $format = 'Test {@methodLong} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterIndexKeywordsTest::keywordMethodLong Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordMethodLongIFormat()
    {
        $format = 'Test {@methodLong} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test m36\StringFormatter\Tests\FormatterIndexKeywordsTest::keywordMethodLongIFormat Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFunction()
    {
        $format = 'Test {@function} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test keywordFunction Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFunctionIFormat()
    {
        $format = 'Test {@function} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test keywordFunctionIFormat Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFile()
    {
        $format = 'Test {@file} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test FormatterIndexKeywordsTest.php Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFileIFormat()
    {
        $format = 'Test {@file} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test FormatterIndexKeywordsTest.php Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFileLong()
    {
        $format = 'Test {@fileLong} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test ' . __FILE__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordFileLongIFormat()
    {
        $format = 'Test {@fileLong} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test ' . __FILE__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDir()
    {
        $format = 'Test {@dir} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test tests Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDirIFormat()
    {
        $format = 'Test {@dir} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test tests Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDirLong()
    {
        $format = 'Test {@dirLong} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test ' . __DIR__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordDirLongIFormat()
    {
        $format = 'Test {@dirLong} Test';
        $res = StringFormatter\iformat($format);
        $this->assertEquals('Test ' . __DIR__ . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordLine()
    {
        $format = 'Test {@line} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile()->unfold(); $line = __LINE__;
        $this->assertEquals('Test ' . $line . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function keywordLineIFormat()
    {
        $format = 'Test {@line} Test';
        $res = StringFormatter\iformat($format)->unfold(); $line = __LINE__;
        $this->assertEquals('Test ' . $line . ' Test', (string) $res);
    }

    /**
     * @test
     */
    public function deeplyNested()
    {
        $this->assertEquals('Test FormatterIndexKeywordsTest::nested4 Test', (string) $this->nested1());
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
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile()->unfold();

        return $res;
    }

    /**
     * @test
     */
    public function deeplyNestedIFormat()
    {
        $this->assertEquals('Test FormatterIndexKeywordsTest::nested4IFormat Test', (string) $this->nested1IFormat());
    }

    protected function nested1IFormat()
    {
        return $this->nested2IFormat();
    }

    protected function nested2IFormat()
    {
        return $this->nested3IFormat();
    }

    protected function nested3IFormat()
    {
        return $this->nested4IFormat();
    }

    protected function nested4IFormat()
    {
        $format = 'Test {@method} Test';
        $res = StringFormatter\iformat($format)->unfold();

        return $res;
    }

    /**
     * @test
     */
    public function combined()
    {
        $format = 'Test {@dir}:{@file}:{@line}:{@method} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile()->unfold(); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterIndexKeywordsTest.php:{$line}:" .
            'FormatterIndexKeywordsTest::combined Test', (string) $res);
    }

    /**
     * @test
     */
    public function combinedIFormat()
    {
        $format = 'Test {@dir}:{@file}:{@line}:{@method} Test';
        $res = StringFormatter\iformat($format)->unfold(); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterIndexKeywordsTest.php:{$line}:" .
            'FormatterIndexKeywordsTest::combinedIFormat Test', (string) $res);
    }

    /**
     * @test
     */
    public function compiledTwice()
    {
        $format = 'Test {@dir}:{@file}:{@line}:{@method} Test';
        $res = StringFormatter\iformat($format);

        $res2 = $res->unfold(); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterIndexKeywordsTest.php:{$line}:" .
            'FormatterIndexKeywordsTest::compiledTwice Test', (string) $res2);

        $res2 = $res->unfold(); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterIndexKeywordsTest.php:{$line}:" .
            'FormatterIndexKeywordsTest::compiledTwice Test', (string) $res2);
    }
}
