<?php

namespace Takemo101\SimpleResultType;

use Closure;
use RuntimeException;
use Throwable;

/**
 * success result class
 *
 * @template S
 * @extends AbstractResult<S,never>
 */
final class Success extends AbstractResult
{
    /**
     * constructor
     *
     * @param S $result
     */
    public function __construct(
        private $result,
    ) {
        //
    }

    /**
     * get success result data
     *
     * @return S
     */
    public function success()
    {
        return $this->result;
    }

    /**
     * get error result data
     *
     * @return never
     */
    public function error()
    {
        throw new RuntimeException('result error: is not error result');
    }

    /**
     * get result type
     *
     * @return Type
     */
    public function type(): Type
    {
        return Type::Success;
    }

    /**
     * on success callback process
     *
     * @param callable(S):void $callback
     * @return static
     */
    public function onSuccess(callable $callback)
    {
        call_user_func($callback, $this->success());

        return $this;
    }

    /**
     * on error callback process
     *
     * @param callable $callback
     * @return static
     */
    public function onError(callable $callback)
    {
        return $this;
    }

    /**
     * map success
     *
     * @template R
     *
     * @param callable(S):R $callback
     * @return Result<R,never>
     */
    public function map(callable $callback): Result
    {
        return new static($callback($this->success()));
    }

    /**
     * flat map success
     *
     * @template R
     * @template F
     *
     * @param callable(S):Result<R,F> $callback
     * @return Result<R,F>
     */
    public function flatMap(callable $callback): Result
    {
        return $callback($this->success());
    }

    /**
     * map error
     *
     * @param callable $callback
     * @return static
     */
    public function mapError(callable $callback): Result
    {
        return $this;
    }

    /**
     * throw exception
     *
     * @return static
     * @throws Throwable
     */
    public function exception()
    {
        return $this;
    }

    /**
     * create a success result.
     *
     * @template R
     *
     * @param R $result
     *
     * @return Success<R>
     */
    public static function create($result = null)
    {
        return new static($result);
    }
}
