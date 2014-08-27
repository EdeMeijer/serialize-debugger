<?php

namespace EdeMeijer\SerializeDebugger\Test;

use EdeMeijer\SerializeDebugger\Debugger;
use stdClass;

class DebuggerTest extends \PHPUnit_Framework_TestCase
{
    /** @var Debugger */
    private $SUT;

    public function setUp()
    {
        $this->SUT = new Debugger();
    }

    /**
     * @dataProvider basicClassificationProvider
     * @param mixed $data
     * @param string $expectedClass
     */
    public function testBasicClassification($data, $expectedClass)
    {
        $result = $this->SUT->debug($data);
        $nodes = $result->getNodes();
        $this->assertCount(1, $nodes);
        $this->assertInstanceOf($expectedClass, $nodes[0]->getType());
    }

    /**
     * @return array
     */
    public function basicClassificationProvider()
    {
        $base = 'EdeMeijer\\SerializeDebugger\\Type\\';
        return [
            [123, $base . 'PrimitiveType'],
            ['text', $base . 'PrimitiveType'],
            [null, $base . 'PrimitiveType'],
            [true, $base . 'PrimitiveType'],
            [[], $base . 'ArrayType'],
            [function () {}, $base . 'ClosureType'],
            [new stdClass(), $base . 'ObjectType'],
            [new Debugger(), $base . 'ObjectType'],
            [fopen(__FILE__, 'r'), $base . 'ResourceType'],
        ];
    }
}
