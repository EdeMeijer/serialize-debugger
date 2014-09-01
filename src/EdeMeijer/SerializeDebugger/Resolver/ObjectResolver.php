<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\ObjectPropertyExtractor;
use EdeMeijer\SerializeDebugger\Type\ObjectType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class ObjectResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @return bool
     */
    protected function supports($data)
    {
        return is_object($data);
    }

    /**
     * @return TypeInterface
     */
    protected function getType()
    {
        return new ObjectType();
    }

    /**
     * @param mixed $data
     * @return mixed[] associative array of properties
     */
    protected function getProperties($data)
    {
        $extractor = new ObjectPropertyExtractor($data);

        // Call __sleep if present
        if (method_exists($data, '__sleep')) {
            $properties = call_user_func([$data, '__sleep']);
        } else {
            $properties = $extractor->getProperties();
        }

        $values = $extractor->getValues($properties);

        // Call __wakeup if present to restore previous state
        if (method_exists($data, '__wakeup')) {
            call_user_func([$data, '__wakeup']);
        }

        return $values;
    }
}