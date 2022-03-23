<?php

namespace Takemo101\SimpleResultType\Support;

use Takemo101\SimpleResultType\CatchType;
use ReflectionFunction;
use Closure;

final class CallableToAttribute
{
    /**
     * @var ReflectionFunction
     */
    private ReflectionFunction $reflection;

    /**
     * constructor
     *
     * @param Closure|string $callable
     */
    private function __construct(
        Closure|string $callable,
    ) {
        $this->reflection = new ReflectionFunction($callable);
    }

    /**
     * to catch error type attribute class
     *
     * @return CatchType|null
     */
    public function toAttribute(): ?CatchType
    {
        $reflections = $this->reflection->getAttributes(CatchType::class);

        if (count($reflections) > 0) {
            $reflection = current($reflections);

            /** @var CatchType */
            $attribute = $reflection->newInstance();

            return $attribute;
        }

        return null;
    }

    /**
     * create constructor
     *
     * @param callable $callable
     * @return self|null
     */
    public static function create(callable $callable): ?self
    {
        if (($callable instanceof Closure) || is_string($callable)) {
            return new self($callable);
        }

        return null;
    }
}
