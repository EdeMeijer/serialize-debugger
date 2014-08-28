<?php

namespace EdeMeijer\SerializeDebugger\Result;

interface FormatterInterface
{
    /**
     * @param ResultItemCollection $result
     * @param bool $verbose
     * @return string
     */
    public function format(ResultItemCollection $result, $verbose = false);
} 