<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Type\SerializableType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;
use Serializable;

class SerializableResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @return bool
     */
    protected function supports($data)
    {
        return $data instanceof Serializable;
    }

    /**
     * @return TypeInterface
     */
    protected function getType()
    {
        return new SerializableType();
    }
}