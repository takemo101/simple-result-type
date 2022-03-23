<?php

namespace Takemo101\SimpleResultType;

use Throwable;

/**
 * result support class
 */
final class Resulter
{
    /**
     * try and catch
     *
     * @template S
     *
     * @param callable():S $callback
     * @return Result<S,Throwable>
     */
    static public function trial(callable $callback): Result
    {
        try {
            /** @var Result<S,never> */
            $result = new Success(call_user_func($callback));
            return $result;
        } catch (Throwable $e) {
            /** @var Result<never,Throwable> */
            $result = new Error($e);
            return $result;
        }
    }

    /**
     * create a success result
     *
     * @template T
     *
     * @param T $result
     * @return Success<T>
     */
    static public function success($result = null): Success
    {
        return Success::create($result);
    }

    /**
     * create a error result
     *
     * @template T
     *
     * @param T $result
     * @return Error<T>
     */
    static public function error($result = null): Error
    {
        return Error::create($result);
    }
}
