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

/**
 * This class is responsible for turning arbitrary data into debug Result objects.
 * Also contains some static utility methods for easy debugging in practice
 *
 * Class Debugger
 * @package EdeMeijer\SerializeDebugger
 */
class Debugger
{
    /**
     * Resolves arbitrary data into a debug Result
     *
     * @param mixed $data
     * @return Result
     */
    public function debug($data)
    {
        // First initialize all the required resolvers
        // The chaining resolver will loop through all it's argument resolvers until one matches, and use that one
        // The recursive resolver will make sure that the process is repeated for newly created child nodes
        $resolver = new RecursiveResolver(
            new ChainingResolver(
                [
                    new ArrayResolver(),
                    new ResourceResolver(),
                    new ClosureResolver(),
                    new SerializableResolver(),
                    new ObjectResolver(),
                    new PrimitiveTypeResolver(),
                ]
            )
        );

        // The context will keep track of processed objects to prevent circular reference issues, and manages
        // node identification
        $context = new Context();

        $rootNode = $context->getNodeForData($data);
        $resolver->resolve($rootNode, $context);

        // Since the context tracks all the nodes in the resolving process, it contains all data needed for a result
        return new Result($context);
    }

    /**
     * Echoes a plain text formatted debug result for given data
     *
     * @param mixed $data
     * @param bool $verbose
     */
    public static function debugPlaintext($data, $verbose = false)
    {
        echo (new PlainTextFormatter())
            ->format((new self())->debug($data), $verbose);
    }

    /**
     * Echoes an HTML formatted debug result for given data
     *
     * @param mixed $data
     * @param bool $verbose
     */
    public static function debugHTML($data, $verbose = false)
    {
        echo (new HTMLFormatter())
            ->format((new self())->debug($data), $verbose);
    }
}
