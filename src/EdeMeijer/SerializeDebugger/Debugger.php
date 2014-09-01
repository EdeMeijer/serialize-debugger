<?php

namespace EdeMeijer\SerializeDebugger;

use EdeMeijer\SerializeDebugger\Resolver\ArrayResolver;
use EdeMeijer\SerializeDebugger\Resolver\ChainingResolver;
use EdeMeijer\SerializeDebugger\Resolver\ClosureResolver;
use EdeMeijer\SerializeDebugger\Resolver\ObjectResolver;
use EdeMeijer\SerializeDebugger\Resolver\PrimitiveTypeResolver;
use EdeMeijer\SerializeDebugger\Resolver\RecursiveResolver;
use EdeMeijer\SerializeDebugger\Resolver\ResourceResolver;
use EdeMeijer\SerializeDebugger\Resolver\SerializableResolver;
use EdeMeijer\SerializeDebugger\Result\HTMLFormatter;
use EdeMeijer\SerializeDebugger\Result\PlainTextFormatter;
use EdeMeijer\SerializeDebugger\Result\Result;

class Debugger
{
    /**
     * @param mixed $data
     * @return Result
     */
    public function getDebugResult($data)
    {
        $resolver = new RecursiveResolver(
            new ChainingResolver(
                [
                    new ArrayResolver(),
                    new ResourceResolver(),
                    new ClosureResolver(),
                    new SerializableResolver(),
                    new ObjectResolver(),
                    new PrimitiveTypeResolver()
                ]
            )
        );

        $context = new Context();
        $rootNode = $context->getNodeForData($data);
        $resolver->resolve($rootNode, $context);
        return new Result($context->getNodes());
    }

    /**
     * @param mixed $data
     * @param bool $verbose
     */
    public static function debugPlaintext($data, $verbose = false)
    {
        echo (new PlainTextFormatter())
            ->format((new self())->getDebugResult($data), $verbose);
    }

    /**
     * @param mixed $data
     * @param bool $verbose
     */
    public static function debugHTML($data, $verbose = false)
    {
        echo (new HTMLFormatter())
            ->format((new self())->getDebugResult($data), $verbose);
    }
}
