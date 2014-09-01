<?php

namespace EdeMeijer\SerializeDebugger\Resolver;

use EdeMeijer\SerializeDebugger\Node;
use EdeMeijer\SerializeDebugger\Context;

class ChainingResolver implements ResolverInterface
{
    /** @var ResolverInterface[] */
    private $resolvers = [];

    /**
     * @param ResolverInterface[] $resolvers
     */
    public function __construct(array $resolvers = [])
    {
        array_walk($resolvers, [$this, 'addResolver']);
    }

    /**
     * @param ResolverInterface $resolver
     * @return $this
     */
    public function addResolver(ResolverInterface $resolver)
    {
        $this->resolvers[] = $resolver;
        return $this;
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
            foreach ($this->resolvers as $resolver) {
                try {
                    return $resolver->resolve($node, $context);
                } catch (TypeNotSupportedException $ex) {
                    // No problem, just try the next one
                }
            }
            throw new TypeNotSupportedException();
        }
        return $node;
    }
}
