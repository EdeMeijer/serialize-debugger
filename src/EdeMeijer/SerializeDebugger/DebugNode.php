<?php

namespace EdeMeijer\SerializeDebugger;

use Closure;
use EdeMeijer\SerializeDebugger\Type\ArrayType;
use EdeMeijer\SerializeDebugger\Type\ClosureType;
use EdeMeijer\SerializeDebugger\Type\ObjectType;
use EdeMeijer\SerializeDebugger\Type\PrimitiveType;
use EdeMeijer\SerializeDebugger\Type\ResourceType;
use EdeMeijer\SerializeDebugger\Type\SerializableType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;
use ReflectionObject;
use ReflectionProperty;
use Serializable;

class DebugNode
{
    /** @var mixed */
    private $data;
    /** @var Tracker */
    private $tracker;
    /** @var TypeInterface */
    private $type;
    /** @var DebugNode */
    private $childNodes = [];
    /** @var bool */
    private $resolved = false;
    /** @var int */
    private $id;

    /**
     * @param mixed $data
     * @param Tracker $tracker
     */
    public function __construct($data, Tracker $tracker)
    {
        $this->data = $data;
        $this->tracker = $tracker;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return DebugNode[]
     */
    public function getChildNodes()
    {
        return $this->childNodes;
    }

    /**
     * @param $key
     * @param DebugNode $node
     * @return $this
     */
    public function addChildNode($key, DebugNode $node)
    {
        $this->childNodes[$key] = $node;
        return $this;
    }

    /**
     * @return TypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return $this
     */
    public function resolve()
    {
        if (!$this->resolved) {
            $this->resolved = true;
            if (is_object($this->data)) {
                $this->resolveObject($this->data);
            } elseif (is_array($this->data)) {
                $this->resolveArray($this->data);
            } elseif (is_resource($this->data)) {
                $this->type = new ResourceType();
            } else {
                $this->type = new PrimitiveType();
            }
        }
        return $this;
    }

    /**
     * @param mixed $data
     */
    private function resolveObject($data)
    {
        if ($data instanceof Closure) {
            $this->type = new ClosureType();
        } elseif ($data instanceof Serializable) {
            $this->type = new SerializableType();
        } else {
            $this->resolveNormalObject($data);
        }
    }

    /**
     * @param $object
     */
    private function resolveNormalObject($object)
    {
        // Normal objects are a bit more complicated. Private and protected properties will be serialized,
        // and they might support the __sleep method that prepares their state.
        // Objects themselves are perfectly safe to serialize
        $this->type = new ObjectType();

        $reflector = new ReflectionObject($object);
        $reflectionProperties = $reflector->getProperties(
            ReflectionProperty::IS_PRIVATE |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PUBLIC
        );

        // Call __sleep if present
        if (method_exists($object, '__sleep')) {
            $properties = call_user_func([$object, '__sleep']);
        } else {
            $properties = [];
            foreach ($reflectionProperties as $reflectionProperty) {
                $properties[] = $reflectionProperty->getName();
            }
        }

        foreach ($properties as $property) {
            $reflectionProperty = $reflector->getProperty($property);
            $reflectionProperty->setAccessible(true);
            $childNode = $this->tracker->getNodeForData($reflectionProperty->getValue($object));
            $reflectionProperty->setAccessible($reflectionProperty->isPublic());
            $this->addChildNode($property, $childNode);
            $childNode->resolve();
        }

        // Call __wakeup if present to restore previous state
        if (method_exists($object, '__wakeup')) {
            call_user_func([$object, '__wakeup']);
        }
    }

    /**
     * @param mixed $data
     */
    private function resolveArray(array $data)
    {
        $this->type = new ArrayType();
        foreach ($data as $key => $value) {
            $childNode = $this->tracker->getNodeForData($value);
            $this->addChildNode($key, $childNode);
            $childNode->resolve();
        }
    }
}