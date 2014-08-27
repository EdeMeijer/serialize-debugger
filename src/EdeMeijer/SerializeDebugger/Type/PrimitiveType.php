<?php

namespace EdeMeijer\SerializeDebugger\Type;

class PrimitiveType extends AbstractType
{
    /**
     * @param mixed $data
     * @return string
     */
    public function getName($data)
    {
        return 'Primitive: ' . gettype($data);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return true;
    }
}