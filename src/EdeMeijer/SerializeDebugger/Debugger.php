<?php

namespace EdeMeijer\SerializeDebugger;

class Debugger
{
    /**
     * @param mixed $data
     * @return DebugResult
     */
    public function debug($data)
    {
        $tracker = new Tracker();
        $tracker->getNodeForData($data)->resolve();
        return new DebugResult($tracker->getNodes());
    }
}
