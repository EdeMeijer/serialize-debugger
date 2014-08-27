<?php

namespace EdeMeijer\SerializeDebugger;

class Tracker
{
    /** DebugNode[] */
    private $nodes = [];
    /** @var DebugNode[] */
    private $objectNodes = [];

    /**
     * @return DebugNode[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param mixed $data
     * @return DebugNode
     */
    public function getNodeForData($data)
    {
        $newNode = new DebugNode($data, $this);

        if (is_object($data)) {
            $hash = spl_object_hash($data);
            if (!isset($this->objectNodes[$hash])) {
                $this->nodes[] = $newNode;
                $this->objectNodes[$hash] = $newNode;
            }
            return $this->objectNodes[$hash];
        }

        $this->nodes[] = $newNode;
        return $newNode;
    }
}