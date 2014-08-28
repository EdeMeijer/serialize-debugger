<?php

namespace EdeMeijer\SerializeDebugger\Test;

use EdeMeijer\SerializeDebugger\Debugger;
use EdeMeijer\SerializeDebugger\Result\PlainTextFormatter;
use EdeMeijer\SerializeDebugger\Result\ResultItem;
use EdeMeijer\SerializeDebugger\Result\SimpleResultItemCollection;
use EdeMeijer\SerializeDebugger\Type\ClosureType;
use EdeMeijer\SerializeDebugger\Type\PrimitiveType;

class PlainTextFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testItFormatsItemsCorrectly()
    {
        $result = $this->givenResultWithValidAndInvalidItems();

        $SUT = new PlainTextFormatter();
        $formatted = $SUT->format($result, true);

        $expected = <<<'TEXT'
Primitive: NULL - safe
Primitive: boolean - safe
	a->b
Closure - ERROR
	a->b
	c[d]
TEXT;
        $this->assertEquals($expected, $formatted);
    }

    public function testItHidesSafeItemsWhenNonVerbose()
    {
        $result = $this->givenResultWithValidAndInvalidItems();

        $SUT = new PlainTextFormatter();
        $formatted = $SUT->format($result, false);

        $expected = <<<'TEXT'
Closure - ERROR
	a->b
	c[d]
TEXT;
        $this->assertEquals($expected, $formatted);
    }

    /**
     * @return SimpleResultItemCollection
     */
    private function givenResultWithValidAndInvalidItems()
    {
        return new SimpleResultItemCollection(
            [
                new ResultItem(null, new PrimitiveType()),
                new ResultItem(true, new PrimitiveType(), ['a->b']),
                new ResultItem(null, new ClosureType(), ['a->b', 'c[d]']),
            ]
        );
    }
}
 