<?php

namespace Takemo101\SimpleResultType;

use Throwable;

/**
 * result interface
 *
 * @template S
 * @template E
 */
interface Result
{
    /**
     * get success result data
     *
     * @return S
     */
    public function success();

    /**
     * get success or result data
     *
     * @template R
     *
     * @param R $or
     * @return S|R
     */
    public function successOr($or = null);

    /**
     * get error result data
     *
     * @return E
     */
    public function error();

    /**
     * get error or result data
     *
     * @template R
     *
     * @param R $or
     * @return E|R
     */
    public function errorOr($or = null);

    /**
     * get result type
     *
     * @return Type
     */
    public function type(): Type;

    /**
     * result is success
     *
     * @return boolean
     */
    public function isSuccess(): bool;

    /**
     * result is error
     *
     * @return boolean
     */
    public function isError(): bool;

    /**
     * on ok callback process
     *
     * @param callable(S):void $callback
     * @return static
     */
    public function onSuccess(callable $callback);

    /**
     * on error callback process
     *
     * @param callable(E):void $callback
     * @return static
     */
    public function onError(callable $callback);

    /**
     * map success
     *
     * @template R
     *
     * @param callable(S):R $callback
     * @return Result<R,never>
     */
    public function map(callable $callback): Result;

    /**
     * flat map success
     *
     * @template R
     * @template F
     *
     * @param callable(S):Result<R,F> $callback
     * @return Result<R,F>
     */
    public function flatMap(callable $callback): Result;

    /**
     * map error
     *
     * @template R
     *
     * @param callable(E):R $callback
     * @return Result<never,R>
     */
    public function mapError(callable $callback): Result;


    /**
     * flat map error
     *
     * @template R
     * @template F
     *
     * @param callable(E):Result<R,F> $callback
     * @return Result<R,F>
     */
    public function flatMapError(callable $callback): Result;

    /**
     * map both process
     *
     * @template R
     * @template F
     *
     * @param callable(S):R $success
     * @param callable(E):F $error
     * @return Result<R,F>
     */
    public function mapBoth(
        callable $success,
        callable $error,
    ): Result;

    /**
     * throw exception
     *
     * @return static
     * @throws Throwable
     */
    public function exception();

    /**
     * output result process
     *
     * @param callable|null $success
     * @param callable|null $error
     * @return mixed
     */
    public function output(
        ?callable $success = null,
        ?callable $error = null,
    ): mixed;
}
