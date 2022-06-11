<?php

namespace Takemo101\SimpleResultType;

use Takemo101\SimpleResultType\Support\CallableToAttribute;
use Takemo101\SimpleResultType\Support\ErrorHandler;
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
    static public function try(callable $callback): Result
    {
        try {
            /** @var S|Result<S,never> */
            $result = call_user_func($callback);

            if ($result instanceof Result) {
                return $result;
            }

            return new Success($result);
        } catch (Throwable $e) {

            $errorType = CallableToAttribute::create($callback)?->toAttribute();

            if ($errorType && !$errorType->included($e)) {
                throw $e;
            }

            /** @var Result<never,Throwable> */
            $result = new Error($e);
            return $result;
        }
    }

    /**
     * try and catch
     *
     * @template S
     *
     * @param callable():S $callback
     * @return Result<S,ErrorHandler>
     */
    static public function trial(callable $callback): Result
    {
        return self::try($callback)->mapError(
            function (Throwable $e): ErrorHandler {
                return new ErrorHandler($e);
            },
        );
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
