<?php

namespace Takemo101\SimpleResultType;

use Closure;
use Throwable;

/**
 * abstract failure
 *
 * @template S
 * @template F
 * @implements Result<S,F>
 */
abstract class Failure implements Result
{
    /**
     * get result data
     *
     * @return null
     */
    final public function success()
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
        return false;
    }

    /**
     * result is failure
     *
     * @return boolean
     */
    final public function isFailure(): bool
    {
        return true;
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
        return $this;
    }

    /**
     * on failure callback process
     *
     * @param Closure $callback
     * @return static
     */
    public function onFailure(Closure $callback)
    {
        $callback($this->failure());

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
        $failure = $this->failure();

        if ($failure instanceof Throwable) {
            throw $failure;
        }

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
        if ($onFailure) {
            return $onFailure($this->failure());
        }

        return null;
    }
}
