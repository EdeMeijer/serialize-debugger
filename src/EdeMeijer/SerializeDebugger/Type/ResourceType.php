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
     * @return int
     */
    public function getLevel()
    {
        return self::LEVEL_WARNING;
    }
}