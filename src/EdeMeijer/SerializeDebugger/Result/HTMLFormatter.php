<?php

namespace EdeMeijer\SerializeDebugger\Result;

class HTMLFormatter extends AbstractFormatter
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
                $res[] = str_repeat('&nbsp;', 4) . $path;
            }
        }
        return implode('<br>', $res);
    }
}