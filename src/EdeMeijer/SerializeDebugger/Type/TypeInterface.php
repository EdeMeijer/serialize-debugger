<?php

namespace EdeMeijer\SerializeDebugger\Type;

interface TypeInterface
{
    const LEVEL_SAFE = 0;
    const LEVEL_WARNING = 1;
    const LEVEL_ERROR = 2;

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
     * @return int one of the level constants
     */
    public function getLevel();
} 