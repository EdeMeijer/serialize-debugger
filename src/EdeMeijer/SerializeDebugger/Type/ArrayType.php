<?php

namespace EdeMeijer\SerializeDebugger\Type;

class ArrayType extends AbstractType
{
    /**
     * @param mixed $data
     * @return string
     */
    public function getName($data)
    {
        return 'Array';
    }

    /**
     * @param string $key
     * @return string
     */
    public function formatKeyAccess($key)
    {
        return '[' . $key . ']';
    }
}