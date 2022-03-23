<?php

namespace Takemo101\SimpleResultType;

use InvalidArgumentException;
use Throwable;

use Attribute;

/**
 * Attribute class for trial processing
 */
#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
final class CatchType
{
    /**
     * @var string[]
     */
    private array $classes = [];

    /**
     * constructor
     *
     * @param string ...$classes
     */
    public function __construct(string ...$classes)
    {
        $classes = array_filter($classes);

        if (!count($classes)) {
            throw new InvalidArgumentException("attribute argument error: no arguments!");
        }

        foreach ($classes as $class) {
            if (!class_exists($class)) {
                throw new InvalidArgumentException("attribute argument error: [{$class}] class does not exist!");
            }
            $this->classes[] = $class;
        }
    }

    /**
     * are exceptions included in the target
     *
     * @param Throwable $e
     * @return boolean
     */
    public function included(Throwable $e): bool
    {
        foreach ($this->classes as $class) {
            if ($e instanceof $class) {
                return true;
            }
        }

        return false;
    }
}
