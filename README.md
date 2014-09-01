# serialize-debugger

A tool for debugging failing or dangerous PHP serialize() calls

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