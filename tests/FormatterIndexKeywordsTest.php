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
    public function keywordClassLong()
    {
        $format = 'Test {@classLong} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test Msztolcman\StringFormatter\Tests\FormatterIndexKeywordsTest Test', (string) $res);
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
    public function keywordMethodLong()
    {
        $format = 'Test {@methodLong} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile();
        $this->assertEquals('Test Msztolcman\StringFormatter\Tests\FormatterIndexKeywordsTest::keywordMethodLong Test', (string) $res);
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
    public function keywordLine()
    {
        $format = 'Test {@line} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(); $line = __LINE__;
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
        $res = $fmt->compile();

        return $res;
    }

    /**
     * @test
     */
    public function combined()
    {
        $format = 'Test {@dir}:{@file}:{@line}:{@method} Test';
        $fmt = new FormatterIndex($format);
        $res = $fmt->compile(); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterIndexKeywordsTest.php:{$line}:" .
            'FormatterIndexKeywordsTest::combined Test', (string) $res);
    }
}
