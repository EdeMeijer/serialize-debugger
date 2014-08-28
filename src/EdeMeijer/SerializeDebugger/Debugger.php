<?php

namespace EdeMeijer\SerializeDebugger;

use EdeMeijer\SerializeDebugger\Result\HTMLFormatter;
use EdeMeijer\SerializeDebugger\Result\PlainTextFormatter;
use EdeMeijer\SerializeDebugger\Result\Result;

class Debugger
{
    /**
     * @param mixed $data
     * @return Result
     */
    public function getDebugResult($data)
    {
        $tracker = new Tracker();
        $tracker->getNodeForData($data)->resolve();
        return new Result($tracker->getNodes());
    }

    /**
     * @param mixed $data
     * @param bool $verbose
     */
    public static function debugPlaintext($data, $verbose = false)
    {
        echo (new PlainTextFormatter())
            ->format((new Debugger())->getDebugResult($data), $verbose);
    }

    /**
     * @param mixed $data
     * @param bool $verbose
     */
    public static function debugHTML($data, $verbose = false)
    {
        echo (new HTMLFormatter())
            ->format((new Debugger())->getDebugResult($data), $verbose);
    }
}
