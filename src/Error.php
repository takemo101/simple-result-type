<?php

namespace Takemo101\SimpleResultType;

use Closure;
use Throwable;

/**
 * error result class
 *
 * @template S
 * @template F
 * @implements Result<S, F>
 */
final class Error implements Result
{
    /**
     * constructor
     *
     * @param Throwable $error
     */
    final public function __construct(
        private Throwable $error,
    ) {
        //
    }

    /**
     * get result data
     *
     * @return null
     */
    public function success()
    {
        return null;
    }

    /**
     * get result data
     *
     * @return null
     */
    public function failure()
    {
        return null;
    }

    /**
     * result is success
     *
     * @return boolean
     */
    public function isSuccess(): bool
    {
        return false;
    }

    /**
     * result is failure
     *
     * @return boolean
     */
    public function isFailure(): bool
    {
        return false;
    }

    /**
     * result is error
     *
     * @return boolean
     */
    public function isError(): bool
    {
        return true;
    }

    /**
     * on success callback process
     *
     * @param Closure $callback
     * @return static
     */
    public function onSuccess(Closure $callback)
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
        return $this;
    }

    /**
     * on error callback process
     *
     * @param Closure $callback
     * @return static
     */
    public function onError(Closure $callback)
    {
        $callback($this->error);

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
        throw $this->error;
    }

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
    ): mixed {
        if ($onError) {
            return $onError($this->error);
        }

        return null;
    }
}
