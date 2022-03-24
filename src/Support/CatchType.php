<?php

namespace Takemo101\SimpleResultType\Support;

use Throwable;
use Attribute;

/**
 * Attribute class for trial processing
 */
#[Attribute(Attribute::TARGET_FUNCTION | Attribute::TARGET_METHOD)]
final class CatchType extends AbstractCatchType
{
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
