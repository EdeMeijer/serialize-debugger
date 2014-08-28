<?php

namespace EdeMeijer\SerializeDebugger;

use EdeMeijer\SerializeDebugger\Type\TypeInterface;

class DebugResult
{
    /** @var Node[] */
    private $nodes = [];
    /** @var (int[])[] */
    private $references = [];
    /** @var bool */
    private $processed = false;

    /**
     * @param Node[] $nodes
     */
    public function __construct(array $nodes)
    {
        $this->nodes = $nodes;
    }

    /**
     * @return Node[]
     */
    public function getNodes()
    {
        $this->process();
        return $this->nodes;
    }

    private function process()
    {
        if (!$this->processed) {
            $this->processed = true;
            foreach ($this->nodes as $nn => $node) {
                $node->setId($nn);
                $this->references[$nn] = [];
            }
            foreach ($this->nodes as $parentNode) {
                foreach ($parentNode->getChildNodes() as $key => $childNode) {
                    $this->references[$childNode->getId()][$parentNode->getId()] = $key;
                }
            }
        }
    }

    /**
     * @param bool $verbose
     * @return string[]
     */
    public function getOutputLines($verbose = false)
    {
        $this->process();
        $res = [];
        foreach ($this->nodes as $node) {
            $type = $node->getType();
            $level = $type->getLevel();
            if ($verbose || $level > TypeInterface::LEVEL_SAFE) {
                $levelIndicator = $this->getLevelIndicator($level);
                $res[] = sprintf(
                    '%s - %s',
                    $type->getName($node->getData()),
                    $levelIndicator
                );

                $referencePaths = $this->getReferencePaths($node);
                foreach ($referencePaths as $path) {
                    $res[] = "\t" . $path;
                }
            }
        }
        return $res;
    }

    /**
     * @param int $level
     * @throws Exception
     * @return string
     */
    private function getLevelIndicator($level)
    {
        if ($level === TypeInterface::LEVEL_SAFE) {
            return 'safe';
        } elseif ($level === TypeInterface::LEVEL_WARNING) {
            return 'WARNING';
        } elseif ($level === TypeInterface::LEVEL_ERROR) {
            return 'ERROR';
        }
        throw new Exception('Invalid level');
    }

    /**
     * @param Node $node
     * @return string[]
     */
    private function getReferencePaths($node)
    {
        $pathData = $this->getReferencePathData($node);
        $result = [];
        foreach ($pathData as $path) {
            $pathString = '';
            for ($pp = 0; $pp < count($path) - 1; $pp++) {
                $parentId = $path[$pp];
                $childId = $path[$pp + 1];
                $parentNode = $this->nodes[$parentId];
                $parentKey = $this->references[$childId][$parentId];
                $pathString .= $parentNode->getType()->formatKeyAccess($parentKey);
            }
            if ($pathString !== '') {
                $result[] = '{root}' . $pathString;
            }
        }
        return $result;
    }

    /**
     * @param Node $node
     * @return array
     */
    private function getReferencePathData($node)
    {
        $frontier = [[$node->getId()]];
        $result = [];

        while (count($frontier) > 0) {
            $newFrontier = [];

            foreach ($frontier as $path) {
                $base = $path[0];
                $parentRefs = $this->references[$base];
                foreach (array_keys($parentRefs) as $parentId) {
                    if (!in_array($parentId, $path)) {
                        $newFrontier[] = array_merge([$parentId], $path);
                    }
                }
                if (count($parentRefs) === 0) {
                    $result[] = $path;
                }
            }
            $frontier = $newFrontier;
        }

        return $result;
    }
}