<?php

namespace EdeMeijer\SerializeDebugger\Test;

use EdeMeijer\SerializeDebugger\Debugger;

class DebugResultTest extends \PHPUnit_Framework_TestCase
{
    /** @var Debugger */
    private $debugger;

    public function setUp()
    {
        $this->debugger = new Debugger();
    }

    public function testNonVerboseOutput()
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

        $SUT = $this->debugger->debug($data);
        $actual = $SUT->getOutputLines(false);

        $expected = [
            'Resource - WARNING',
            "\t" . '{root}->prop[sub][resource]',
            'Closure - ERROR',
            "\t" . '{root}->closure',
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testVerboseOutput()
    {
        $data = [1, 2, [true]];

        $SUT = $this->debugger->debug($data);
        $actual = $SUT->getOutputLines(true);

        $expected = [
            'Array - safe',
            'Primitive: integer - safe',
            "\t" . '{root}[0]',
            'Primitive: integer - safe',
            "\t" . '{root}[1]',
            'Array - safe',
            "\t" . '{root}[2]',
            'Primitive: boolean - safe',
            "\t" . '{root}[2][0]',
        ];

        $this->assertEquals($expected, $actual);
    }
}
 