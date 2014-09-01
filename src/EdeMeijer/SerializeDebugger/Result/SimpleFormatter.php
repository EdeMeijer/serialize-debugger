<?php

namespace EdeMeijer\SerializeDebugger\Result;

abstract class SimpleFormatter extends AbstractFormatter
{

    /**
     * @param ResultItem[] $items
     * @return string
     */
    protected function doFormat(array $items)
    {
        $res = [];
        foreach ($items as $item) {
            $type = $item->getType();
            $level = $type->getLevel();

            $levelIndicator = $this->getLevelIndicator($level);
            $res[] = sprintf(
                '%s - %s',
                $type->getName($item->getData()),
                $levelIndicator
            );
            foreach ($item->getReferencePaths() as $path) {
                $res[] = str_repeat($this->getSpace(), 4) . $path;
            }
        }
        return implode($this->getEOL(), $res) . $this->getEOL();
    }

    /**
     * @return string
     */
    abstract protected function getEOL();

    /**
     * @return string
     */
    abstract protected function getSpace();
}