<?php

namespace EdeMeijer\SerializeDebugger;

use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class Node
{
    /** @var int */
    private $id;
    /** @var mixed */
    private $data;
    /** @var TypeInterface */
    private $type;
    /** @var Node */
    private $childNodes = [];

    /**
     * @param int $id
     * @param mixed $data
     */
    public function __construct($id, $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Node[]
     */
    public function getChildNodes()
    {
        return $this->childNodes;
    }

    /**
     * @param $key
     * @param Node $node
     * @return $this
     */
    public function addChildNode($key, Node $node)
    {
        $this->childNodes[$key] = $node;
        return $this;
    }

    /**
     * @return TypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param TypeInterface $type
     * @return $this
     */
    public function setType(TypeInterface $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasType()
    {
        return $this->type !== null;
    }
}
