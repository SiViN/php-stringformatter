StringFormatter
===============

`StringFormatter` library allows you to powerful formatting of your strings.

Current stable version
----------------------

0.1.0

PHP version
-----------

`StringFormatter` works with PHP 5.3+ or PHP 7.0+

Usage
-----

Simplest call:

    $f = new FormatterIndex('{} {}!');
    echo $f->parse('Hello', 'world')->eol(); # Hello world!
    
Simple replacement with strict sequence:
 
    $f = new FormatterIndex('{1} {0}!');
    echo $f->parse('world', 'Hello')->eol(); # Hello world!
    
Text alignment - left:
 
    $f = new FormatterIndex('{} "{1:<20}"');
    echo $f->parse('Hello', 'world')->eol(); # Hello "world               "
    
Text alignment - right:
 
    $f = new FormatterIndex('{} "{1:>20}"');
    echo $f->parse('Hello', 'world')->eol(); # Hello "               world"
    
Text alignment - center:

    $f = new FormatterIndex('{} "{1:^20}"');
    echo $f->parse('Hello', 'world')->eol(); # Hello "       world        "
    
Text alignment with specified character:
 
    $f = new FormatterIndex('{} "{1:*^20}"');
    echo $f->parse('Hello', 'world')->eol(); # Hello "*******world********"
    
Sprintf-like formatting:
 
    $f = new FormatterIndex('Test: {%%.3f}');
    echo $f->parse(2.1234567)->eol(); # Test: 2.123
    $f = new FormatterIndex('Test 2: {%%c}');
    echo $f->parse(97)->eol(); # Test2: a
    
Call object method or get object property:
 
    $f = new FormatterIndex('Test: {0->method} {->property}');
    class TestStringFormatter {
        public $property = 'test property';
        public function method() {
            return 'test method';
        }
    }
    echo $f->parse(new TestStringFormatter(), new TestStringFormatter())->eol(); # Test: test method test property
    
Convert int to other base:
 
    $f = new FormatterIndex('Test: 10: {#d}, 16: {0#x}, 2: {0#b}');
    echo $f->parse(11)->eol(); # Test: 10: 11, 16: b, 2: 1011
    $f = new FormatterIndex('Test: 10: {#10}, 16: {0#16}, 2: {0#2}, 7: {0#7}');
    echo $f->parse(11)->eol(); # Test: 10: 11, 16: b, 2: 1011, 7: 14
    
Available bases:
    * b - binary
    * o - octal
    * d - decimal
    * x - hex (small letters)
    * X - hex (big letters)

Array indexes:
 
    $f = new FormatterIndex('Test: test1: {[test1]}, test2: {0[test2]}');
    echo $f->parse(array('test1' => 'Hello', 'test2' => 'world'))->eol(); # Test: test1: Hello, test2: world

Parameters to formatter can be passed both: when creating object, or when running `FormatterIndex::parse` method. The last one has higher priority. 

There is also named version of templates. You can use in template, instead of numeric indexes, named arguments, like:

     $f = new FormatterNamed('{hello} {name}!');

In this case, you must use `FormatterNamed` class instead of `FormatterIndex` to parse this kind of template. This works with one argument only, an array.
Keys in that array are related to named tokens in your tenplate, in above example there is: 'hello' and 'name'. Example of use:
     
     echo $f->parse(array('name' => 'world', 'hello' => 'Hello'); # Hello world!

The same mechanism works with every previous example, but there is no automatic (`{}`) tokens!

But named version of formatter has additional ability: you can create formatter object with predefined some (or all) placeholders, and pass to `FormatterNamed::parse` method only these not filled before, or some to overwrite:

    $f = new FormatterNamed('{hello}, {name}!', ['hello' => 'Hi']);
    echo $f->parse(['name' => 'Thomas'])->eol(); # Hi, Thomas!
    echo $f->parse(['name' => 'Martha'])->eol(); # Hi, Martha!
    echo $f->parse(['hello' => 'Welcome', 'name' => 'Bruce'])->eol(); # Welcome, Bruce!

`FormatterIndex::parse` and `FormatterNamed::parse` returns instance of `Transformer` class, that has some additional methods that allows us transform formatted string in some way:

    $f = new FormatterNamed('{hello}, {name}!', ['hello' => 'Hi']);
    $data = $f->parse(['name' => 'Thomas']);
    echo $data->eol(); # Hi, Thomas!
    echo $data->upper()->eol(); HI, THOMAS! 
    echo $data->lower()->eol(); hi, thomas! 
    echo $data->replace('!', '?')->eol(); Hi, Thomas? 
    echo $data->replace(['!', ','], ['?', '|'])->eol(); Hi| Thomas? 

Installation
------------

Use composer:

    composer require msztolcman/stringformatter 

Voila!

Authors
-------

Marcin Sztolcman <marcin@urzenia.net>

Contact
-------

If you like or dislike this software, please do not hesitate to tell me about
this me via email (marcin@urzenia.net).

If you find bug or have an idea to enhance this tool, please use GitHub's
[issues](https://github.com/msztolcman/php-stringformatter/issues).

License
-------

The MIT License (MIT)

Copyright (c) 2016 Marcin Sztolcman

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

ChangeLog
---------

### v0.5.0

* First public version
