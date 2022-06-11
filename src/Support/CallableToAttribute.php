<?php

namespace Takemo101\SimpleResultType\Support;

use ReflectionFunction;
use ReflectionAttribute;
use Closure;

final class CallableToAttribute
{
    /**
     * constructor
     *
     * @param ReflectionFunction $reflection
     */
    private function __construct(
        private ReflectionFunction $reflection,
    ) {
        //
    }

    /**
     * to catch error type attribute class
     *
     * @return Catchable|null
     */
    public function toAttribute(): ?Catchable
    {
        $reflections = $this->reflection->getAttributes(
            Catchable::class,
            ReflectionAttribute::IS_INSTANCEOF,
        );

        if (count($reflections) > 0) {
            $reflection = current($reflections);

            /** @var Catchable */
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
            return new self(new ReflectionFunction($callable));
        }

        return null;
    }
}
