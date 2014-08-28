<?php

namespace EdeMeijer\SerializeDebugger;

class Debugger
{
    /**
     * @param mixed $data
     * @return Result
     */
    public function debug($data)
    {
        $tracker = new Tracker();
        $tracker->getNodeForData($data)->resolve();
        return new Result($tracker->getNodes());
    }
}
