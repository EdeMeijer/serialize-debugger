<?php

namespace EdeMeijer\SerializeDebugger;

class Context
{
    /** DebugNode[] */
    private $nodes = [];
    /** @var Node[] */
    private $objectNodes = [];
    /** @var int */
    private $idSeq = 0;

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
        if (is_object($data)) {
            $hash = spl_object_hash($data);
            if (!isset($this->objectNodes[$hash])) {
                $newNode = $this->createNode($data);
                $this->nodes[] = $newNode;
                $this->objectNodes[$hash] = $newNode;
            }
            return $this->objectNodes[$hash];
        }

        $newNode = $this->createNode($data);
        $this->nodes[] = $newNode;
        return $newNode;
    }

    /**
     * @param mixed $data
     * @return Node
     */
    private function createNode($data)
    {
        return new Node($this->idSeq++, $data);
    }
}