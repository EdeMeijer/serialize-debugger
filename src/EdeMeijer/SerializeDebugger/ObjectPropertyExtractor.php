<?php

namespace EdeMeijer\SerializeDebugger;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

class ObjectPropertyExtractor
{
    /** @var object */
    private $object;
    /** @var ReflectionClass */
    private $reflector;
    /** @var mixed[] */
    private $propertyMap;

    /**
     * @param object $object
     * @throws InvalidArgumentException
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new InvalidArgumentException('First argument must a an object, got ' . gettype($object));
        }
        $this->object = $object;
        $this->reflector = new ReflectionObject($object);
    }

    /**
     * @return string[]
     */
    public function getProperties()
    {
        $this->initializePropertyMap();
        return array_keys($this->propertyMap);
    }

    /**
     * @param string $property
     * @throws Exception
     * @return mixed
     */
    public function getValue($property)
    {
        $this->initializePropertyMap();
        if (!array_key_exists($property, $this->propertyMap)) {
            throw new Exception('Property ' . $property . ' not found');
        }
        return $this->propertyMap[$property];
    }

    /**
     * @param string[] $properties
     * @return mixed[]
     * @throws Exception
     */
    public function getValues(array $properties)
    {
        $result = [];
        foreach ($properties as $property) {
            $result[$property] = $this->getValue($property);
        }
        return $result;
    }

    private function initializePropertyMap()
    {
        if ($this->propertyMap === null) {
            $this->propertyMap = [];
            $reflectionProperties = $this->getAccessibleReflectionProperties($this->reflector);
            $this->initializePropertiesFromReflectionProperties($this->reflector, $reflectionProperties);
        }
    }

    /**
     * @param ReflectionClass $reflector
     * @param ReflectionProperty[] $reflectionProperties
     */
    private function initializePropertiesFromReflectionProperties(
        ReflectionClass $reflector,
        array $reflectionProperties
    ) {
        foreach ($reflectionProperties as $reflectionProperty) {
            $reflectionProperty->setAccessible(true);
            $value = $reflectionProperty->getValue($this->object);
            $reflectionProperty->setAccessible($reflectionProperty->isPublic());

            $this->propertyMap[$reflectionProperty->getName()] = $value;
        }

        $this->initializeParentPrivateProperties($reflector);
    }

    /**
     * @param ReflectionClass $reflector
     */
    private function initializeParentPrivateProperties(ReflectionClass $reflector)
    {
        $parentReflector = $reflector->getParentClass();
        if ($parentReflector instanceof ReflectionClass) {
            $reflectionProperties = $this->getPrivateReflectionProperties($parentReflector);
            $this->initializePropertiesFromReflectionProperties($parentReflector, $reflectionProperties);
        }
    }

    /**
     * @param ReflectionClass $reflector
     * @return ReflectionProperty[]
     */
    private function getAccessibleReflectionProperties(ReflectionClass $reflector)
    {
        return $this->getWithoutStatic($reflector->getProperties());
    }

    /**
     * @param ReflectionClass $reflector
     * @return ReflectionProperty[]
     */
    private function getPrivateReflectionProperties(ReflectionClass $reflector)
    {
        return $this->getWithoutStatic($reflector->getProperties(ReflectionProperty::IS_PRIVATE));
    }

    /**
     * @param ReflectionProperty[] $properties
     * @return ReflectionProperty[]
     */
    private function getWithoutStatic(array $properties)
    {
        $filtered = [];
        foreach ($properties as $property) {
            if (!$property->isStatic()) {
                $filtered[] = $property;
            }
        }
        return $filtered;
    }
}