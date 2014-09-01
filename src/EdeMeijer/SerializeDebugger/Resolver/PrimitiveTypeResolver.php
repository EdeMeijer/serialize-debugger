<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Type\PrimitiveType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class PrimitiveTypeResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @return bool
     */
    protected function supports($data)
    {
        return
            is_string($data) ||
            is_int($data) ||
            is_float($data) ||
            is_bool($data) ||
            $data === null;
    }

    /**
     * @return TypeInterface
     */
    protected function getType()
    {
        return new PrimitiveType();
    }
}