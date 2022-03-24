<?php

namespace Takemo101\SimpleResultType\Support;

use InvalidArgumentException;
use Throwable;

/**
 * abstract catch type class for trial processing
 */
abstract class AbstractCatchType implements Catchable
{
    /**
     * @var string[]
     */
    protected array $classes = [];

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
}
