<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use Closure;
use EdeMeijer\SerializeDebugger\Type\ClosureType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class ClosureResolver extends AbstractResolver
{
    /**
     * @param mixed $data
     * @return bool
     */
    protected function supports($data)
    {
        return $data instanceof Closure;
    }

    /**
     * @return TypeInterface
     */
    protected function getType()
    {
        return new ClosureType();
    }
}