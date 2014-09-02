<?php

namespace EdeMeijer\SerializeDebugger\Test\Unit\Resolver;

use EdeMeijer\SerializeDebugger\Context;
use EdeMeijer\SerializeDebugger\Resolver\AbstractResolver;
use EdeMeijer\SerializeDebugger\Type\PrimitiveType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;
use PHPUnit_Framework_MockObject_MockObject;

class AbstractResolverTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractResolver|PHPUnit_Framework_MockObject_MockObject */
    private $SUT;
    /** @var Context */
    private $context;

    public function setUp()
    {
        $this->SUT = $this->getMockBuilder('EdeMeijer\SerializeDebugger\Resolver\AbstractResolver')
            ->setMethods(['getProperties'])
            ->getMockForAbstractClass();

        $this->context = new Context();
    }

    public function testItSetsTheNodeTypeIfSupported()
    {
        $type = new PrimitiveType();
        $this->givenTypeSupportedReturns(true);
        $this->givenGetTypeReturns($type);
        $this->givenGetPropertiesReturns([]);
        $node = $this->context->getNodeForData(null);

        $this->SUT->resolve($node, $this->context);

        $this->assertEquals($type, $node->getType());
    }

    public function testItAddsChildNodesBasedOnProperties()
    {
        $properties = ['a' => 1, 'b' => 2];
        $this->givenTypeSupportedReturns(true);
        $this->givenGetTypeReturns(new PrimitiveType());
        $this->givenGetPropertiesReturns($properties);
        $node = $this->context->getNodeForData(null);

        $resolvedNode = $this->SUT->resolve($node, $this->context);

        $this->assertCount(2, $resolvedNode->getChildNodes());
        $this->assertCount(3, $this->context->getNodes());
    }

    /**
     * @expectedException \EdeMeijer\SerializeDebugger\Resolver\TypeNotSupportedException
     */
    public function testItThrowsExceptionWhenTypeIsNotSupported()
    {
        $this->givenTypeSupportedReturns(false);
        $node = $this->context->getNodeForData(null);

        $this->SUT->resolve($node, $this->context);
    }

    /**
     * @param bool $supported
     */
    private function givenTypeSupportedReturns($supported)
    {
        $this->SUT->expects($this->any())
            ->method('supports')
            ->will($this->returnValue($supported));
    }

    /**
     * @param TypeInterface $type
     */
    private function givenGetTypeReturns(TypeInterface $type)
    {
        $this->SUT->expects($this->any())
            ->method('getType')
            ->will($this->returnValue($type));
    }

    /**
     * @param mixed[] $properties
     */
    private function givenGetPropertiesReturns(array $properties)
    {
        $this->SUT->expects($this->any())
            ->method('getProperties')
            ->will($this->returnValue($properties));
    }
}
 