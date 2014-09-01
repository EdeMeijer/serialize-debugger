<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Node;
use EdeMeijer\SerializeDebugger\Context;

interface ResolverInterface
{
    /**
     * @param Node $node
     * @param Context $context
     * @throws TypeNotSupportedException
     * @return Node the resolved node
     */
    public function resolve(Node $node, Context $context);
} 