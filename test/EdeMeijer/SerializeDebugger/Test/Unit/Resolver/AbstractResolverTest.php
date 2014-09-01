<?php

namespace EdeMeijer\SerializeDebugger\Test\Unit\Resolver;

use EdeMeijer\SerializeDebugger\Context;
use EdeMeijer\SerializeDebugger\Node;
use EdeMeijer\SerializeDebugger\Resolver\AbstractResolver;
use PHPUnit_Framework_MockObject_MockObject;

class AbstractResolverTest extends \PHPUnit_Framework_TestCase
{
    /** @var AbstractResolver|PHPUnit_Framework_MockObject_MockObject */
    private $SUT;
    /** @var Context */
    private $context;

    public function setUp()
    {
        $this->SUT = $this->getMockForAbstractClass('EdeMeijer\SerializeDebugger\Resolver\AbstractResolver');
        $this->context = new Context();
    }

    /**
     * @expectedException \EdeMeijer\SerializeDebugger\Resolver\TypeNotSupportedException
     */
    public function testItThrowsExceptionWhenTypeIsNotSupported()
    {
        $this->givenTypeSupportedReturns(false);
        $node = new Node(0, true);
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
}
 