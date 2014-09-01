<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Type\ResourceType;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class ResourceResolver extends AbstractResolver {

    /**
     * @param mixed $data
     * @return bool
     */
    protected function supports($data)
    {
        return is_resource($data);
    }

    /**
     * @return TypeInterface
     */
    protected function getType()
    {
        return new ResourceType();
    }
}