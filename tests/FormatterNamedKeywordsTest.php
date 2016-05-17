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

use Msztolcman\StringFormatter\FormatterNamed;

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
    public function keywordClassLong()
    {
        $format = 'Test {@classLong} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test Msztolcman\StringFormatter\Tests\FormatterNamedKeywordsTest Test', (string) $res);
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
    public function keywordMethodLong()
    {
        $format = 'Test {@methodLong} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile();
        $this->assertEquals('Test Msztolcman\StringFormatter\Tests\FormatterNamedKeywordsTest::keywordMethodLong Test', (string) $res);
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
    public function combined()
    {
        $format = 'Test {@dir}:{@file}:{@line}:{@method} Test';
        $fmt = new FormatterNamed($format);
        $res = $fmt->compile(); $line = __LINE__;
        $this->assertEquals("Test tests:FormatterNamedKeywordsTest.php:{$line}:" .
            'FormatterNamedKeywordsTest::combined Test', (string) $res);
    }
}
