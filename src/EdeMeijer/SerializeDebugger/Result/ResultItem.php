<?php

namespace EdeMeijer\SerializeDebugger\Result;

use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class ResultItem
{
    /** @var mixed */
    private $data;
    /** @var TypeInterface */
    private $type;
    /** @var string[] */
    private $referencePaths;

    /**
     * @param mixed $data
     * @param TypeInterface $type
     * @param string[] $referencePaths
     */
    public function __construct($data, TypeInterface $type, array $referencePaths = [])
    {
        $this->data = $data;
        $this->type = $type;
        $this->referencePaths = $referencePaths;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return TypeInterface
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getReferencePaths()
    {
        return $this->referencePaths;
    }
} 