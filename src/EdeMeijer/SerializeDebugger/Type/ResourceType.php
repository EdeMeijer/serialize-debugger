<?php

namespace EdeMeijer\SerializeDebugger\Type;

class ResourceType extends AbstractType
{
    /**
     * @param mixed $data
     * @return string
     */
    public function getName($data)
    {
        return 'Resource';
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return false;
    }
}