<?php

namespace Takemo101\SimpleResultType;

use Closure;
use Throwable;

/**
 * abstract success
 *
 * @template S
 * @template F
 * @implements Result<S,F>
 */
abstract class Success implements Result
{
    /**
     * get result data
     *
     * @return null
     */
    final public function failure()
    {
        return null;
    }

    /**
     * result is success
     *
     * @return boolean
     */
    final public function isSuccess(): bool
    {
        return true;
    }

    /**
     * result is failure
     *
     * @return boolean
     */
    final public function isFailure(): bool
    {
        return false;
    }

    /**
     * result is error
     *
     * @return boolean
     */
    final public function isError(): bool
    {
        return false;
    }

    /**
     * on success callback process
     *
     * @param Closure $callback
     * @return static
     */
    final public function onSuccess(Closure $callback)
    {
        $callback($this->success());

        return $this;
    }

    /**
     * on failure callback process
     *
     * @param Closure $callback
     * @return static
     */
    final public function onFailure(Closure $callback)
    {
        return $this;
    }

    /**
     * on error callback process
     *
     * @param Closure $callback
     * @return static
     */
    final public function onError(Closure $callback)
    {
        return $this;
    }

    /**
     * throw exception
     *
     * @return static
     * @throws Throwable
     */
    final public function exception()
    {
        return $this;
    }

    /**
     * on result callback process
     *
     * @param Closure|null $onSuccess
     * @param Closure|null $onFailure
     * @param Closure|null $onError
     * @return mixed
     */
    final public function on(
        ?Closure $onSuccess = null,
        ?Closure $onFailure = null,
        ?Closure $onError = null,
    ): mixed {
        if ($onSuccess) {
            return $onSuccess($this->success());
        }

        return null;
    }
}
