<?php

namespace EdeMeijer\SerializeDebugger\Test;

use EdeMeijer\SerializeDebugger\Debugger;
use EdeMeijer\SerializeDebugger\Test\Mocks\TestSubClass;
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

    public function testItReadsAllKindsOfObjectPropertiesCorrectly()
    {
        require_once __DIR__ . '/Mocks/TestBaseClass.php';
        require_once __DIR__ . '/Mocks/TestSubClass.php';

        $objWithInheritance = new TestSubClass();
        $nodes = $this->SUT->debug($objWithInheritance)->getRawNodes();
        $childNodes = $nodes[0]->getChildNodes();

        // We expect 8 nodes; the base object and 7 properties
        $this->assertCount(8, $nodes);
        // Check if all expected properties are indexed
        $this->assertArrayHasKey('privateSubVar', $childNodes);
        $this->assertArrayHasKey('protectedSubVar', $childNodes);
        $this->assertArrayHasKey('publicSubVar', $childNodes);
        $this->assertArrayHasKey('overriddenBaseVar', $childNodes);
        $this->assertArrayHasKey('protectedBaseVar', $childNodes);
        $this->assertArrayHasKey('publicBaseVar', $childNodes);
        $this->assertArrayHasKey('privateBaseVar', $childNodes);
        // Check if value of overridden property is the one of the sub class
        $this->assertEquals('def', $childNodes['overriddenBaseVar']->getData());
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
            [$this->getMock('Serializable'), $base . 'SerializableType']
        ];
    }
}
