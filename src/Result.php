<?php

namespace Takemo101\SimpleResultType;

use Closure;
use Throwable;

/**
 * result interface
 *
 * @template S
 * @template F
 */
interface Result
{
    /**
     * get result data
     *
     * @return S
     */
    public function success();

    /**
     * get result data
     *
     * @return F
     */
    public function failure();

    /**
     * result is success
     *
     * @return boolean
     */
    public function isSuccess(): bool;

    /**
     * result is failure
     *
     * @return boolean
     */
    public function isFailure(): bool;

    /**
     * result is error
     *
     * @return boolean
     */
    public function isError(): bool;

    /**
     * on success callback process
     *
     * @param Closure $callback
     * @return static
     */
    public function onSuccess(Closure $callback);

    /**
     * on failure callback process
     *
     * @param Closure $callback
     * @return static
     */
    public function onFailure(Closure $callback);

    /**
     * on error callback process
     *
     * @param Closure $callback
     * @return static
     */
    public function onError(Closure $callback);

    /**
     * throw exception
     *
     * @return static
     * @throws Throwable
     */
    public function exception();

    /**
     * on result callback process
     *
     * @param Closure|null $onSuccess
     * @param Closure|null $onFailure
     * @param Closure|null $onError
     * @return mixed
     */
    public function on(
        ?Closure $onSuccess = null,
        ?Closure $onFailure = null,
        ?Closure $onError = null,
    ): mixed;
}
