<?php

namespace Takemo101\SimpleResultType\Support;

use ReflectionFunction;
use RuntimeException;
use ReflectionType;
use ReflectionUnionType;
use ReflectionIntersectionType;
use ReflectionNamedType;

/**
 * Compare the types of Callback arguments
 */
final class CallbackArgumentTypeComparer
{
    /**
     * constructor
     *
     * @param ReflectionFunction $reflection
     * @throws RuntimeException
     */
    public function __construct(
        private ReflectionFunction $reflection,
    ) {
        if ($reflection->getNumberOfParameters() == 0) {
            throw new RuntimeException('callback has no arguments');
        }
    }

    /**
     * compare
     *
     * @param object $obj
     * @return boolean
     */
    public function equals(object $obj): bool
    {
        $type = $this->getArgumentType();
        $classes = $this->fromTypeToClasses($type);

        foreach ($classes as $class) {
            if ($obj instanceof $class) {
                return true;
            }
        }

        return false;
    }

    /**
     * get argument reflection type
     *
     * @return ReflectionType
     */
    private function getArgumentType(): ReflectionType
    {
        if ($type = $this->reflection->getParameters()[0]->getType()) {
            return $type;
        }

        throw new RuntimeException('artgument type is empty');
    }

    /**
     * Get the class name from the reflection type
     *
     * @param ReflectionType $type
     * @return string[]
     * @throws RuntimeException
     */
    private function fromTypeToClasses(ReflectionType $type): array
    {
        if ($type instanceof ReflectionIntersectionType) {
            throw new RuntimeException('intersection type is not supported!');
        }

        if ($type instanceof ReflectionUnionType) {
            return array_map(function (ReflectionNamedType $type) {
                return $type->getName();
            }, $type->getTypes());
        }

        if ($type instanceof ReflectionNamedType) {
            return [$type->getName()];
        }

        $class = get_class($type);

        throw new RuntimeException("[{$class}] type is not supported!");
    }
}
