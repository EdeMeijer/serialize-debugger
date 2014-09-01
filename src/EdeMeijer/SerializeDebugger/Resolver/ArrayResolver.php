<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Type\ArrayType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class ArrayResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @return bool
     */
    protected function supports($data)
    {
        return is_array($data);
    }

    /**
     * @return TypeInterface
     */
    protected function getType()
    {
        return new ArrayType();
    }

    /**
     * @param mixed $data
     * @return mixed[]
     */
    protected function getProperties($data)
    {
        return $data;
    }
}