# serialize-debugger

A tool for debugging failing or dangerous PHP serialize() calls.

# Motivation

Debugging issues with PHP's `serialize()` method can be a tedious task, because it doesn't give much information when it
encounters errors. This is best demonstrated with an example:

```php
<?php

$nestedStruct = [
    'foo' => (object) [
        'validProp' => 42,
        'invalidProp' => function () {},
    ],
    'bar' => [
        'someProp' => 'Hello',
    ],
];

$serialized = serialize($nestedStruct);
```

The above code will result in a fatal error:


```
PHP Fatal error:  Uncaught exception 'Exception' with message 'Serialization of 'Closure' is not allowed' in /path/to/script.php:13
Stack trace:
#0 /path/to/script.php(13): serialize(Array)
#1 {main}
  thrown in /path/to/script.php on line 13
```

Not very helpful, is it? Where is this closure located exactly? Imagine this happening with big, nested structures, and you're in for a lot of in-the-dark debugging fun.

This is where serialize-debugger can lend a helping hand. Look at the following PHP snippet and it's output:

```php
Debugger::debugPlaintext($nestedStruct);
```

```
Closure - ERROR
    {root}[foo]->invalidProp
```

Much better, now we know exactly where the offending closure is located in the problematic data structure.

# Requirements

serialize-debugger is only supported on PHP 5.4.0 and up.

# Installation

Using composer, add to your composer.json:
```
"edemeijer/serialize-debugger": ">=0.1.1"
```
Then run `composer.phar update`
#API
Basic usage:
```php
// There are two static convenience methods in the main debugger class:
// Outputting errors and warnings in plain text format
Debugger::debugPlaintext($data);

// Outputting errors and warnings in HTML format
Debugger::debugHTML($data);

// Both methods provide an optional second boolean parameter for verbose output
// This will output not only errors and warnings, but the paths to every element
// in the data set
Debugger::debugPlainText($data, true);
```
Without using the static convenience methods:
```php
$debugger = new Debugger();
$formatter = new PlainTextFormatter();
$result = $debugger->getDebugResult($data, $verbose);
echo $formatter->format($result);
```

# License
MIT - See LICENSE file.