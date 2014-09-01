<?php

namespace EdeMeijer\SerializeDebugger\Result;

class PlainTextFormatter extends SimpleFormatter
{
    /**
     * @return string
     */
    protected function getEOL()
    {
        return PHP_EOL;
    }

    /**
     * @return string
     */
    protected function getSpace()
    {
        return ' ';
    }
}