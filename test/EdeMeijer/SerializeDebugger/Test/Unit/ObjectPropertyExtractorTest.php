<?php


namespace EdeMeijer\SerializeDebugger\Test\Unit;


use EdeMeijer\SerializeDebugger\ObjectPropertyExtractor;
use EdeMeijer\SerializeDebugger\Test\Mocks\TestSubClass;

class ObjectPropertyExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtraction()
    {
        require_once __DIR__ . '/Mocks/TestBaseClass.php';
        require_once __DIR__ . '/Mocks/TestSubClass.php';

        $objWithInheritance = new TestSubClass();

        $SUT = new ObjectPropertyExtractor($objWithInheritance);

        $extractedProperties = $SUT->getProperties();

        // We expect 7 properties, no static properties
        $this->assertCount(7, $extractedProperties);
        // Check if all expected properties are indexed
        $this->assertContains('privateSubVar', $extractedProperties);
        $this->assertContains('protectedSubVar', $extractedProperties);
        $this->assertContains('publicSubVar', $extractedProperties);
        $this->assertContains('overriddenBaseVar', $extractedProperties);
        $this->assertContains('protectedBaseVar', $extractedProperties);
        $this->assertContains('publicBaseVar', $extractedProperties);
        $this->assertContains('privateBaseVar', $extractedProperties);
        // Check if value of overridden property is the one of the sub class
        $overriddenBaseVar = $SUT->getValue('overriddenBaseVar');
        $this->assertEquals('def', $overriddenBaseVar);
    }
}
 