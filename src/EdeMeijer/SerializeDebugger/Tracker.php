<?php

namespace EdeMeijer\SerializeDebugger;

class Tracker
{
    /** DebugNode[] */
    private $nodes = [];
    /** @var Node[] */
    private $objectNodes = [];

    /**
     * @return Node[]
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * @param mixed $data
     * @return Node
     */
    public function getNodeForData($data)
    {
        $newNode = new Node($data, $this);

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