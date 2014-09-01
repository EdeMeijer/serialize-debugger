<?php

namespace EdeMeijer\SerializeDebugger\Test\Mocks;

class TestBaseClass
{
    /** @var int */
    private $privateBaseVar = 123;
    /** @var int */
    protected $protectedBaseVar = 456;
    /** @var int */
    public $publicBaseVar = 789;
    /** @var string */
    public $overriddenBaseVar = 'abc';
} 