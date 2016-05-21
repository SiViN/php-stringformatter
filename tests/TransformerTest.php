<?php

/*
 * This file is part of m36/stringformatter.
 *
 * (c) 36monkeys <http://36monkeys.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @version 0.5.3
 */

namespace m36\StringFormatter\Tests;

use m36\StringFormatter\Transformer;

class TransformerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function replace()
    {
        $src = 'abcabd';
        $tr = new Transformer($src);
        $res = $tr->replace('b', 'Z');
        $this->assertEquals('aZcaZd', (string) $res);
    }

    /**
     * @test
     */
    public function ireplace()
    {
        $src = 'aBcaBd';
        $tr = new Transformer($src);
        $res = $tr->ireplace('b', 'Z');
        $this->assertEquals('aZcaZd', (string) $res);
    }

    /**
     * @test
     */
    public function strip()
    {
        $src = " \n\r\tabcabd\t\r\n ";
        $tr = new Transformer($src);
        $res = $tr->strip();
        $this->assertEquals('abcabd', (string) $res);
    }

    /**
     * @test
     */
    public function stripOtherCharmask()
    {
        $src = '--abcabd--';
        $tr = new Transformer($src);
        $res = $tr->strip('-');
        $this->assertEquals('abcabd', (string) $res);
    }

    /**
     * @test
     */
    public function lstrip()
    {
        $src = " \n\r\tabcabd\t\r\n ";
        $tr = new Transformer($src);
        $res = $tr->lstrip();
        $this->assertEquals("abcabd\t\r\n ", (string) $res);
    }

    /**
     * @test
     */
    public function lstripOtherCharmask()
    {
        $src = '--abcabd--';
        $tr = new Transformer($src);
        $res = $tr->lstrip('-');
        $this->assertEquals('abcabd--', (string) $res);
    }

    /**
     * @test
     */
    public function rstrip()
    {
        $src = " \n\r\tabcabd\t\r\n ";
        $tr = new Transformer($src);
        $res = $tr->rstrip();
        $this->assertEquals(" \n\r\tabcabd", (string) $res);
    }

    /**
     * @test
     */
    public function rstripOtherCharmask()
    {
        $src = '--abcabd--';
        $tr = new Transformer($src);
        $res = $tr->rstrip('-');
        $this->assertEquals('--abcabd', (string) $res);
    }

    /**
     * @test
     */
    public function upper()
    {
        $src = 'abcabd';
        $tr = new Transformer($src);
        $res = $tr->upper();
        $this->assertEquals('ABCABD', (string) $res);
    }

    /**
     * @test
     */
    public function lower()
    {
        $src = 'ABCABD';
        $tr = new Transformer($src);
        $res = $tr->lower();
        $this->assertEquals('abcabd', (string) $res);
    }

    /**
     * @test
     */
    public function upperFirst()
    {
        $src = 'abc abd';
        $tr = new Transformer($src);
        $res = $tr->upperFirst();
        $this->assertEquals('Abc abd', (string) $res);
    }

    /**
     * @test
     */
    public function lowerFirst()
    {
        $src = 'ABC ABD';
        $tr = new Transformer($src);
        $res = $tr->lowerFirst();
        $this->assertEquals('aBC ABD', (string) $res);
    }

    /**
     * @test
     */
    public function upperWords()
    {
        $src = 'abc abd';
        $tr = new Transformer($src);
        $res = $tr->upperWords();
        $this->assertEquals('Abc Abd', (string) $res);
    }

    /**
     * @test
     */
    public function wordWrap()
    {
        $src = 'The quick brown fox jumped over the lazy dog.';
        $tr = new Transformer($src);
        $res = $tr->wordWrap(20, "<br />\n");
        $this->assertEquals("The quick brown fox<br />\n" .
            "jumped over the lazy<br />\n" .
            'dog.', (string) $res);
    }

    /**
     * @test
     */
    public function substr()
    {
        $src = 'abcdef';
        $tr = new Transformer($src);

        $res = $tr->substr(0, -1);
        $this->assertEquals('abcde', (string) $res);

        $res = $tr->substr(2, -1);
        $this->assertEquals('cde', (string) $res);

        $res = $tr->substr(4, -4);
        $this->assertEquals('', (string) $res);

        $res = $tr->substr(-3, -1);
        $this->assertEquals('de', (string) $res);
    }

    /**
     * @test
     */
    public function eol()
    {
        $src = 'abc';
        $tr = new Transformer($src);

        $res = $tr->eol();
        $this->assertEquals('abc' . PHP_EOL, (string) $res);
    }

    /**
     * @test
     */
    public function eolrn()
    {
        $src = 'abc';
        $tr = new Transformer($src);

        $res = $tr->eolrn();
        $this->assertEquals("abc\r\n", (string) $res);
    }

    /**
     * @test
     */
    public function eoln()
    {
        $src = 'abc';
        $tr = new Transformer($src);

        $res = $tr->eoln();
        $this->assertEquals("abc\n", (string) $res);
    }

    /**
     * @test
     */
    public function prefix()
    {
        $src = 'abc';
        $tr = new Transformer($src);

        $res = $tr->prefix('ALA');
        $this->assertEquals('ALAabc', (string) $res);
    }

    /**
     * @test
     */
    public function suffix()
    {
        $src = 'abc';
        $tr = new Transformer($src);

        $res = $tr->suffix('ALA');
        $this->assertEquals('abcALA', (string) $res);
    }
}
