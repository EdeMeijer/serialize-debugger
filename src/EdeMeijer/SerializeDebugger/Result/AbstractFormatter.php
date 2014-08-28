<?php

namespace EdeMeijer\SerializeDebugger\Result;

use EdeMeijer\SerializeDebugger\Exception;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;

abstract class AbstractFormatter implements FormatterInterface
{
    /**
     * @param ResultItemCollection $result
     * @param bool $verbose
     * @return string
     */
    public function format(ResultItemCollection $result, $verbose = false)
    {
        $visibleItems = [];
        foreach ($result->getItems() as $item) {
            $type = $item->getType();
            $level = $type->getLevel();
            if ($verbose || $level > TypeInterface::LEVEL_SAFE) {
                $visibleItems[] = $item;
            }
        }
        return $this->doFormat($visibleItems);
    }

    /**
     * @param ResultItem[] $items
     * @return string
     */
    abstract protected function doFormat(array $items);

    /**
     * @param int $level
     * @throws Exception
     * @return string
     */
    protected function getLevelIndicator($level)
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
}