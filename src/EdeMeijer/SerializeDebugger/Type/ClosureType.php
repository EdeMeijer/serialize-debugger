<?php

namespace EdeMeijer\SerializeDebugger\Type;

class ClosureType extends AbstractType
{
    /**
     * @param mixed $data
     * @return string
     */
    public function getName($data)
    {
        return 'Closure';
    }

    /**
     * @return int
     */
    public function getLevel()
    {
        return self::LEVEL_ERROR;
    }
}