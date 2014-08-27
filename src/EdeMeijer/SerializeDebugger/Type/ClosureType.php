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
     * @return bool
     */
    public function isValid()
    {
        return false;
    }
}