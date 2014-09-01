<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Node;
use EdeMeijer\SerializeDebugger\Context;
use EdeMeijer\SerializeDebugger\Type\TypeInterface;

abstract class AbstractResolver implements ResolverInterface
{
    /**
     * @param Node $node
     * @param Context $context
     * @throws TypeNotSupportedException
     * @return Node the resolved node
     */
    public function resolve(Node $node, Context $context)
    {
        if (!$node->hasType()) {
            $data = $node->getData();
            if ($this->supports($data)) {
                $node->setType($this->getType());
                foreach ($this->getProperties($data) as $key => $value) {
                    $childNode = $context->getNodeForData($value);
                    $node->addChildNode($key, $childNode);
                }
            } else {
                throw new TypeNotSupportedException();
            }
        }
        return $node;
    }

    /**
     * @param mixed $data
     * @return bool
     */
    abstract protected function supports($data);

    /**
     * @return TypeInterface
     */
    abstract protected function getType();

    /**
     * @param mixed $data
     * @return mixed[] associative array of properties
     */
    protected function getProperties($data)
    {
        return [];
    }
}