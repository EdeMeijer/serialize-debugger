<?php

namespace EdeMeijer\SerializeDebugger\Type;

use EdeMeijer\SerializeDebugger\Exception;

abstract class AbstractType implements TypeInterface
{
    /**
     * @param string $key
     * @throws Exception
     * @return string
     */
    public function formatKeyAccess($key)
    {
        throw new Exception('Not implemented');
    }
} 