<?php

namespace EdeMeijer\SerializeDebugger\Result;

class SimpleResultItemCollection implements ResultItemCollection
{
    /**
     * @param ResultItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return ResultItem[]
     */
    public function getItems()
    {
        return $this->items;
    }
}