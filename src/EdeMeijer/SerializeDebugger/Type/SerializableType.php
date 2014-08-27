<?php

namespace EdeMeijer\SerializeDebugger\Type;

class SerializableType extends AbstractType
{
    /**
     * @param mixed $data
     * @return string
     */
    public function getName($data)
    {
        return 'Serializable: ' . get_class($data);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return true;
    }
}