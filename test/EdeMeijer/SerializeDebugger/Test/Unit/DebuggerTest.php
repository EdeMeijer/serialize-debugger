<?php

namespace EdeMeijer\SerializeDebugger\Test\Unit;

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
        $nodes = $this->SUT->debug($data)->getRawNodes();
        $this->assertCount(1, $nodes);
        $this->assertInstanceOf($expectedClass, $nodes[0]->getType());
    }

    public function testArrayItemHandling()
    {
        $nodes = $this->SUT->debug(['aaa', 'bbb'])->getRawNodes();
        // We expect 3 nodes, the array and both the strings
        $this->assertCount(3, $nodes);
        $arrayNode = $nodes[0];
        $stringNodes = array_slice($nodes, 1);
        $this->assertEquals($stringNodes, $arrayNode->getChildNodes());
    }

    public function testSimpleObjectPropertyHandling()
    {
        $nodes = $this->SUT->debug((object)['a' => 'aaa', 'b' => 'bbb'])->getRawNodes();
        // We expect 3 nodes, the object and both the strings
        $this->assertCount(3, $nodes);
        $objectNode = $nodes[0];
        $stringNodes = array_slice($nodes, 1);
        $this->assertEquals($stringNodes, array_values($objectNode->getChildNodes()));
    }

    public function testItHandlesCircularReferencesCorrectly()
    {
        $a = new stdClass();
        $b = new stdClass();
        $a->bRef = $b;
        $b->aRef = $a;
        $nodes = $this->SUT->debug($a)->getRawNodes();

        $this->assertCount(2, $nodes);
        $this->assertCount(1, $nodes[0]->getChildNodes());
        $this->assertCount(1, $nodes[1]->getChildNodes());
        $this->assertSame($nodes[1], $nodes[0]->getChildNodes()['bRef']);
        $this->assertSame($nodes[0], $nodes[1]->getChildNodes()['aRef']);
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
            [
                function () {
                },
                $base . 'ClosureType'
            ],
            [new stdClass(), $base . 'ObjectType'],
            [new Debugger(), $base . 'ObjectType'],
            [fopen(__FILE__, 'r'), $base . 'ResourceType'],
            [$this->getMock('Serializable'), $base . 'SerializableType']
        ];
    }
}
