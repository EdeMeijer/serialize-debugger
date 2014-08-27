<?php

namespace EdeMeijer\SerializeDebugger\Type;

interface TypeInterface
{
    /**
     * @param mixed $data
     * @return string
     */
    public function getName($data);

    /**
     * @param string $key
     * @return string
     */
    public function formatKeyAccess($key);

    /**
     * @return bool
     */
    public function isValid();
} 