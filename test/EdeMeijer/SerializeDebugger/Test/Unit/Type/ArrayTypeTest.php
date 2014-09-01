<?php

namespace EdeMeijer\SerializeDebugger\Test\Unit\Type;

use EdeMeijer\SerializeDebugger\Type\ArrayType;

class ArrayTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testKeyAccessFormat()
    {
        $SUT = new ArrayType();
        $formattedKey = $SUT->formatKeyAccess('test');
        $this->assertEquals('[test]', $formattedKey);
    }
}
 