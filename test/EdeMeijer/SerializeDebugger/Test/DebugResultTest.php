<?php

namespace EdeMeijer\SerializeDebugger\Test;

use EdeMeijer\SerializeDebugger\Debugger;
use EdeMeijer\SerializeDebugger\Result\ResultItem;
use EdeMeijer\SerializeDebugger\Type\ArrayType;
use EdeMeijer\SerializeDebugger\Type\ClosureType;
use EdeMeijer\SerializeDebugger\Type\ObjectType;
use EdeMeijer\SerializeDebugger\Type\PrimitiveType;
use EdeMeijer\SerializeDebugger\Type\ResourceType;

class DebugResultTest extends \PHPUnit_Framework_TestCase
{
    /** @var Debugger */
    private $debugger;

    public function setUp()
    {
        $this->debugger = new Debugger();
    }

    public function testDebugResult()
    {
        $data = (object)[
            'prop' => [
                'sub' => [
                    'resource' => fopen(__FILE__, 'r')
                ],
                'test' => null
            ],
            'test' => false,
            'closure' => function() {}
        ];

        $SUT = $this->debugger->getDebugResult($data);
        $actual = $SUT->getItems();

        $expected = [
            new ResultItem($data, new ObjectType()),
            new ResultItem($data->prop, new ArrayType(), ['{root}->prop']),
            new ResultItem($data->test, new PrimitiveType(), ['{root}->test']),
            new ResultItem($data->closure, new ClosureType(), ['{root}->closure']),
            new ResultItem($data->prop['sub'], new ArrayType(), ['{root}->prop[sub]']),
            new ResultItem($data->prop['test'], new PrimitiveType(), ['{root}->prop[test]']),
            new ResultItem($data->prop['sub']['resource'], new ResourceType(), ['{root}->prop[sub][resource]']),
        ];

        $this->assertEquals($expected, $actual);
    }
}
 