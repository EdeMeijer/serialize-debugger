<?php

namespace EdeMeijer\SerializeDebugger\Type;

class ObjectType extends AbstractType
{
    /**
     * @param mixed $data
     * @return string
     */
    public function getName($data)
    {
        return 'Object: ' . get_class($data);
    }

    /**
     * @param string $key
     * @return string
     */
    public function formatKeyAccess($key)
    {
        return '->' . $key;
    }
}