<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Node;
use EdeMeijer\SerializeDebugger\Context;

class RecursiveResolver implements ResolverInterface
{
    /** @var ResolverInterface */
    private $baseResolver;

    /**
     * @param ResolverInterface $baseResolver
     */
    public function __construct(ResolverInterface $baseResolver)
    {
        $this->baseResolver = $baseResolver;
    }

    /**
     * @param Node $node
     * @param Context $context
     * @throws TypeNotSupportedException
     * @return Node the resolved node
     */
    public function resolve(Node $node, Context $context)
    {
        if (!$node->hasType()) {
            $node = $this->baseResolver->resolve($node, $context);
            foreach ($node->getChildNodes() as $childNodes) {
                $this->resolve($childNodes, $context);
            }
        }
        return $node;
    }
}