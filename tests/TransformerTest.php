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
    public function replaceCallback()
    {
        $src = 'abcabd';
        $tr = new Transformer($src);
        $res = $tr->replace('b', function($from, $trfm) { return strtoupper($from); });
        $this->assertEquals('aBcaBd', (string) $res);
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
    public function ireplaceCallback()
    {
        $src = 'aBcaBd';
        $tr = new Transformer($src);
        $res = $tr->ireplace('b', function($from, $trfm) { return strtoupper("!{$from}!"); });
        $this->assertEquals('a!B!ca!B!d', (string) $res);
    }

    /**
     * @test
     */
    public function regexReplaceString()
    {
        $src = 'aBcaBd';
        $tr = new Transformer($src);
        $res = $tr->regexReplace('/([A-Z])/', '${1}!');
        $this->assertEquals('aB!caB!d', (string) $res);
    }

    /**
     * @test
     */
    public function regexReplaceCallback()
    {
        $src = 'aBcaBd';
        $tr = new Transformer($src);
        $res = $tr->regexReplace('/([A-Z])/', function ($matches) { return "{$matches[0]}!"; });
        $this->assertEquals('aB!caB!d', (string) $res);
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
    public function upperAscii()
    {
        $src = 'abcabd';
        $tr = new Transformer($src);
        $res = $tr->upper();
        $this->assertEquals('ABCABD', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function upperUtf8()
    {
        $src = 'ąbćąbd';
        $tr = new Transformer($src);
        $res = $tr->upper('UTF-8');
        $this->assertEquals('ĄBĆĄBD', (string) $res);
    }

    /**
     * @test
     */
    public function lowerAscii()
    {
        $src = 'ABCABD';
        $tr = new Transformer($src);
        $res = $tr->lower();
        $this->assertEquals('abcabd', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function lowerUtf8()
    {
        $src = 'ĄBĆĄBD';
        $tr = new Transformer($src);
        $res = $tr->lower('UTF-8');
        $this->assertEquals('ąbćąbd', (string) $res);
    }

    /**
     * @test
     */
    public function upperFirstAscii()
    {
        $src = 'abc abd';
        $tr = new Transformer($src);
        $res = $tr->upperFirst();
        $this->assertEquals('Abc abd', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function upperFirstUtf8()
    {
        $src = 'ąbć ąbd';
        $tr = new Transformer($src);
        $res = $tr->upperFirst('UTF-8');
        $this->assertEquals('Ąbć ąbd', (string) $res);
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
     * @requires extension mbstring
     */
    public function lowerFirstUtf8()
    {
        $src = 'ĄBĆ ĄBD';
        $tr = new Transformer($src);
        $res = $tr->lowerFirst('UTF-8');
        $this->assertEquals('ąBĆ ĄBD', (string) $res);
    }

    /**
     * @test
     */
    public function upperWordsAscii()
    {
        $src = 'abc abd aBE';
        $tr = new Transformer($src);
        $res = $tr->upperWords();
        $this->assertEquals('Abc Abd ABE', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function upperWordsUtf8()
    {
        $this->markTestSkipped('not finished yet');
        $src = 'ąbć ćbd ęBĘ';
        $tr = new Transformer($src);
        $res = $tr->upperWords(null, 'UTF-8');
        $this->assertEquals('Ąbć Ćbd ĘBĘ', (string) $res);
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
    public function substrAscii()
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
     * @requires extension mbstring
     */
    public function substrUtf8()
    {
        $src = 'ąbćdęf';
        $tr = new Transformer($src);

        $res = $tr->substr(0, -1, 'UTF-8');
        $this->assertEquals('ąbćdę', (string) $res);

        $res = $tr->substr(2, -1, 'UTF-8');
        $this->assertEquals('ćdę', (string) $res);

        $res = $tr->substr(4, -4, 'UTF-8');
        $this->assertEquals('', (string) $res);

        $res = $tr->substr(-3, -1, 'UTF-8');
        $this->assertEquals('dę', (string) $res);
    }

    /**
     * @test
     */
    public function repeat()
    {
        $src = 'abc.';
        $tr = new Transformer($src);

        $res = $tr->repeat(3);
        $this->assertEquals('abc.abc.abc.', (string) $res);
    }

    /**
     * @test
     */
    public function reverseAscii()
    {
        $src = 'abcdef';
        $tr = new Transformer($src);

        $res = $tr->reverse();
        $this->assertEquals('fedcba', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function reverseUtf8()
    {
        $src = 'ąbćdęf';
        $tr = new Transformer($src);

        $res = $tr->reverse('UTF-8');
        $this->assertEquals('fędćbą', (string) $res);
    }

    /**
     * @test
     */
    public function squashWhitechars()
    {
        $src = "  ąb \tć\r\nd ęf\t\r\n ";
        $tr = new Transformer($src);

        $res = $tr->squashWhitechars();
        $this->assertEquals('ąb ć d ęf', (string) $res);
    }

    /**
     * @test
     */
    public function indexAscii()
    {
        $src = 'abef';
        $tr = new Transformer($src);

        $res = $tr->insert('cd', 2);
        $this->assertEquals('abcdef', (string) $res);

        $res = $tr->insert('cd', -2);
        $this->assertEquals('cdabef', (string) $res);

        $res = $tr->insert('cd', 8);
        $this->assertEquals('abefcd', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function indexUtf8()
    {
        $src = 'ąbęf';
        $tr = new Transformer($src);

        $res = $tr->insert('ćd', 2, 'UTF-8');
        $this->assertEquals('ąbćdęf', (string) $res);

        $res = $tr->insert('ćd', -2, 'UTF-8');
        $this->assertEquals('ćdąbęf', (string) $res);

        $res = $tr->insert('ćd', 8, 'UTF-8');
        $this->assertEquals('ąbęfćd', (string) $res);
    }

    /**
     * @test
     */
    public function ensurePrefixAscii()
    {
        $src = 'abef';
        $tr = new Transformer($src);

        $res = $tr->ensurePrefix('ab', 'UTF-8');
        $this->assertEquals('abef', (string) $res);

        $res = $tr->ensurePrefix('ćw', 'UTF-8');
        $this->assertEquals('ćwabef', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function ensurePrefixUtf8()
    {
        $src = 'ąbęf';
        $tr = new Transformer($src);

        $res = $tr->ensurePrefix('ąb', 'UTF-8');
        $this->assertEquals('ąbęf', (string) $res);

        $res = $tr->ensurePrefix('ćw', 'UTF-8');
        $this->assertEquals('ćwąbęf', (string) $res);
    }

    /**
     * @test
     */
    public function ensureSuffixAscii()
    {
        $src = 'abef';
        $tr = new Transformer($src);

        $res = $tr->ensureSuffix('ef', 'UTF-8');
        $this->assertEquals('abef', (string) $res);

        $res = $tr->ensureSuffix('ćw', 'UTF-8');
        $this->assertEquals('abefćw', (string) $res);
    }

    /**
     * @test
     * @requires extension mbstring
     */
    public function ensureSuffixUtf8()
    {
        $src = 'ąbęf';
        $tr = new Transformer($src);

        $res = $tr->ensureSuffix('ęf', 'UTF-8');
        $this->assertEquals('ąbęf', (string) $res);

        $res = $tr->ensureSuffix('ćw', 'UTF-8');
        $this->assertEquals('ąbęfćw', (string) $res);
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
