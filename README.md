StringFormatter
===============

`StringFormatter` - simple but powerfull string formatting.

Current stable version
----------------------

0.5.4

PHP version
-----------

`StringFormatter` works with PHP 5.3+ or PHP 7.0+

Usage
-----

`StringFormatter` is composed with few classes, where manually You will use
just two of them. Both require `format` being used to `compile` final string,
and some `params` (optional) to fill placeholders in `format`. Placeholder is
surrounded by braces, and contain index (`FormatterIndex` class) or name
(`FormatterNamed` class), and optional modifiers. 

Simplest usage can be like:

    $f = new FormatterIndex('{0} {1}!');
    echo $f->compile('Hello', 'world')->eol(); # Hello world!
    
There we are using `FormatterIndex` class, so we will use indexed placeholders.
There are defined two placeholders: `0` and `1`. In some cases, we can omit
indexes:

    $f = new FormatterIndex('{} {}!');
    echo $f->compile('Hello', 'world')->eol(); # Hello world!
    
Result of this two pieces of code is exactly the same.

Of course, we can change order of used arguments:

    $f = new FormatterIndex('{1} {0}!');
    echo $f->compile('Hello', 'world')->eol(); # world Hello!
    
Parameters for placeholders we can also pass in constructor:

    $f = new FormatterIndex('{} {}!', 'Hello', 'world');
    echo $f->compile()->eol(); # Hello world!

There is also `FormatterNamed` class, when we don't want to use indexes for
selecting objects for placeholders:

    $f = new FormatterNamed('{hello} {name}!');
    echo $f->compile(['name' => 'world', 'hello' => 'Hello']); # Hello world!
    
Here, similar to `FormatterIndex` class, we can also pass arguments in
constructor. Also by default both arrays are merged into one, and used for
placeholders:
 
    $f = new FormatterNamed('{hello}, {name}!', ['hello' => 'Hi']);
    echo $f->compile(['name' => 'Thomas'])->eol(); # Hi, Thomas!
    echo $f->compile(['name' => 'Martha'])->eol(); # Hi, Martha!
    echo $f->compile(['hello' => 'Welcome', 'name' => 'Bruce'])->eol(); # Welcome, Bruce!

As a result of `IFormatter::compile` method we got an instance of `Transformer`
class, that have some additional possibilities (described below). 

Shortcuts
---------

Pst, there is more ;)

Both classes have also functional API. Instead of manually creating object,
call a method etc, You can use functions:
 
    $adj = 'glorious';
    $char = '!';
    echo iformat('Some {} method{}', [$adj, $char])->eol(); 
    echo iformatl('Some {} method{}', $adj, $char)->eol(); 
    echo nformat('Some {adj} method{char}', ['adj' => $adj, 'char' => $char])->eol(); 

`iformat` and `iformatl` differs only with parameters format (array or list).  

For PHP 5.6.0 or newer, you can import just this functions:

    use function m36\StringFormatter\iformat;
    use function m36\StringFormatter\iformatl;
    use function m36\StringFormatter\nformat;
    
And use them as well :)

Formatters
----------

Text alignment - left:
 
    $f = new FormatterIndex('{} "{1:<20}"');
    echo $f->compile('Hello', 'world')->eol(); # Hello "world               "
    
Text alignment - right:
 
    $f = new FormatterIndex('{} "{1:>20}"');
    echo $f->compile('Hello', 'world')->eol(); # Hello "               world"
    
Text alignment - center:

    $f = new FormatterIndex('{} "{1:^20}"');
    echo $f->compile('Hello', 'world')->eol(); # Hello "       world        "
    
Text alignment with specified character:
 
    $f = new FormatterIndex('{} "{1:*^20}"');
    echo $f->compile('Hello', 'world')->eol(); # Hello "*******world********"
    
Sprintf-like formatting (handle all specifiers `sprintf()` do):
 
    $f = new FormatterIndex('Test: {%%.3f}');
    echo $f->compile(2.1234567)->eol(); # Test: 2.123
    $f = new FormatterIndex('Test 2: {%%c}');
    echo $f->compile(97)->eol(); # Test2: a
    
Call object method or get object property:
 
    $f = new FormatterIndex('Test: {0->method} {->property}');
    class TestStringFormatter {
        public $property = 'test property';
        public function method() {
            return 'test method';
        }
    }
    echo $f->compile(new TestStringFormatter(), new TestStringFormatter())->eol(); # Test: test method test property
    
Convert int to other base:
 
    $f = new FormatterIndex('Test: 10: {#d}, 16: {0#x}, 2: {0#b}');
    echo $f->compile(11)->eol(); # Test: 10: 11, 16: b, 2: 1011
    $f = new FormatterIndex('Test: 10: {#10}, 16: {0#16}, 2: {0#2}, 7: {0#7}');
    echo $f->compile(11)->eol(); # Test: 10: 11, 16: b, 2: 1011, 7: 14
    
Available bases:
  * `b` - binary
  * `o` - octal
  * `d` - decimal
  * `x` - hex (small letters)
  * `X` - hex (big letters)

Array indexes:
 
    $f = new FormatterIndex('Test: test1: {[test1]}, test2: {0[test2]}');
    echo $f->compile(array('test1' => 'Hello', 'test2' => 'world'))->eol(); # Test: test1: Hello, test2: world

Keywords
--------

As a placeholder, you can use one of followed keywords (modifiers are not accepted there):

  * `@class` - replaced by current class name (without namespace). Will trigger E_USER_WARNING if used outside of class.
  * `@classLong` - replaced by current class name (with namespace). Will trigger E_USER_WARNING if used outside of class.
  * `@method` - replaced by current class name (without namespace) and method
    name. Will trigger E_USER_WARNING if used outside of class.
  * `@methodLong` - replaced by current class name (with namespace) and method
    name. Will trigger E_USER_WARNING if used outside of class.
  * `@function` - replaced by current function/method name (without namespace and class name). Will trigger E_USER_WARNING if used outside of function.
  * `@file` - file name where `IFormatter::compile` is called (without parents)
  * `@fileLong` - full path to file where `IFormatter::compile` is called (with parents)
  * `@dir` - directory name of file where `IFormatter::compile` is called (without parents)
  * `@dirLong` - directory name of file where `IFormatter::compile` is called (with parents)
  * `@line` - line number in file where `IFormatter::compile` is called (without parents)

Transformers
------------

As a return of `IFormatter::compile` we got and instance of `Transformer` class.
There are defined some simple and useful transformers for parsed string:
 
  * `replace` - wrapper for `str_replace` 
  * `ireplace` - wrapper for `ireplace` 
  * `regexReplace` - wrapper for preg_replace or preg_replace_callback (depends on
   `$replacement` being .callback or not) 
  * `strip` - wrapper for `trim` 
  * `lstrip` - wrapper for `ltrim` 
  * `rstrip` - wrapper for `rtrim` 
  * `upper` - wrapper for `strtoupper` 
  * `lower` - wrapper for `strtolower` 
  * `upperFirst` - wrapper for `ucfirst` 
  * `lowerFirst` - wrapper for `lcfirst` 
  * `upperWords` - wrapper for `ucwords` 
  * `wordWrap` - wrapper for `wordwrap` 
  * `substr` - wrapper for `substr` 
  * `eol` - append `PHP_EOL` at the end of string 
  * `eoln` - append `\n` at the end of string 
  * `eolrn` - append `\r\n` at the end of string
  * `suffix` - append given string to the end of current value
  * `prefix` - prepend given string to the beginning of current value
   
`Transformer` is immutable, what means after every transformation it return
always new instance of itself. 

Some examples
-------------

    // assume it's result of $request->query->all() from Symfony
    $data = ['customerId' => 2, 'customerName' => 'John', 'customerLastName' => 'Wayne', 'age' => 24];
    echo nformat('[{@file}:{@line}] Incoming data for customerId #{customerId}: first name: {customerName}, ' .
        'last name: {customerLastName}, age: {age}', $data)->eol();
    # [example.php:12] Incoming data for customerId #2: first name: John, last name: Wayne, age: 24
    
    // fetch data about package from Doctrine
    $package = $this->em->find(Package::class, $request->get('packageId'));
    $logger->info(nformat('[{@method}] Package id {packageId} found with data: {package->getName}, {package->getTechnology} created at {package->getCreateDate}', ['packageId' => $request->get('packageId'), 'package' => $package]));
    # [Example::test] Package id 4 found with data: m36/StringFormatter, php created at 2016-05-19 12:02:16
    
    $f = new FormatterNamed('{hello}, {name}!', ['hello' => 'Hi']);
    $data = $f->compile(['name' => 'Thomas']);
    echo $data->eol(); # Hi, Thomas!
    echo $data->upper()->eol(); HI, THOMAS! 
    echo $data->lower()->eol(); hi, thomas! 
    echo $data->replace('!', '?')->eol(); Hi, Thomas? 
    echo $data->replace(['!', ','], ['?', '@'])->eol(); Hi@ Thomas?

Installation
------------

Use composer:

    composer require m36/stringformatter 

Voila!

Authors
-------

Marcin Sztolcman <m.sztolcman@36monkeys.com>

Contact
-------

If you like or dislike this software, please do not hesitate to tell me about
this me via email (m.sztolcman@36monkeys.com).

If you find bug or have an idea to enhance this tool, please use GitHub's
[issues](https://github.com/36monkeys/php-stringformatter/issues).

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

### (dev)

* many transformers uses Multibyte String module if available
* added new transformers: `Transformer::regexReplace`, `Transformer::repeat`,
    `Transformer::reverse`, `Transformer::squashWhitechars`,
    `Transformer::insert`, `Transformer::ensurePrefix`,
    `Transformer::ensureSuffix`

### v0.5.4

* missing changelog

### v0.5.3

* fixed handling some keywords usages outside a class or function

### v0.5.2

* fixed composer.json version

### v0.5.1

* using @keywords in functional API was broken for some keywords

### v0.5.0

* First public version
