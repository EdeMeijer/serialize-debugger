<?php

namespace EdeMeijer\SerializeDebugger\Test\Unit\Type;

use EdeMeijer\SerializeDebugger\Type\ObjectType;

class ObjectTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testKeyAccessFormat()
    {
        $SUT = new ObjectType();
        $formattedKey = $SUT->formatKeyAccess('test');
        $this->assertEquals('->test', $formattedKey);
    }
}
 