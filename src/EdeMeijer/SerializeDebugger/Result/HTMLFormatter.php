<?php

namespace EdeMeijer\SerializeDebugger\Result;

class HTMLFormatter extends SimpleFormatter
{
    /**
     * @return string
     */
    protected function getEOL()
    {
        return '<br>';
    }

    /**
     * @return string
     */
    protected function getSpace()
    {
        return '&nbsp;';
    }
}