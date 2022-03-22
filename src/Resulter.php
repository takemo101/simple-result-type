<?php

namespace Takemo101\SimpleResultType;

use Takemo101\SimpleResultType\None\Success;
use Closure;
use Throwable;

/**
 * resulter class
 */
final class Resulter
{
    /**
     * watch and error catch
     *
     * @param Closure $callback
     * @return Result<mixed,mixed>
     */
    static public function watch(Closure $callback): Result
    {
        try {
            $result = $callback();
            return is_null($result) ? new Success : $result;
        } catch (Throwable $e) {
            return new Error($e);
        }
    }
}
