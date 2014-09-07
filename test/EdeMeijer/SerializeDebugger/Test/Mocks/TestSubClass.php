<?php

namespace EdeMeijer\SerializeDebugger\Test\Mocks;

class TestSubClass extends TestBaseClass
{
    /** @var int */
    private $privateSubVar = 123;
    /** @var int */
    protected $protectedSubVar = 456;
    /** @var int */
    public $publicSubVar = 789;
    /** @var string */
    public $overriddenBaseVar = 'def';

    /** @var string */
    public static $publicStaticSubVar = 'ghi';
    /** @var string */
    protected static $protectedStaticSubVar = 'jkl';
    /** @var string */
    private static $privateStaticSubVar = 'jkl';
} 