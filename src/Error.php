<?php

namespace Takemo101\SimpleResultType;

use RuntimeException;
use Takemo101\SimpleResultType\Support\ErrorHandler;
use Throwable;

/**
 * error result class
 *
 * @template E
 * @extends AbstractResult<never,E>
 * @immutable
 */
final class Error extends AbstractResult
{
    /**
     * constructor
     *
     * @param E $result
     */
    public function __construct(
        private $result,
    ) {
        //
    }

    /**
     * get success result data
     *
     * @return never
     */
    public function success()
    {
        throw new RuntimeException('result error: is not success result');
    }

    /**
     * get error result data
     *
     * @return E
     */
    public function error()
    {
        return $this->result;
    }


    /**
     * get result type
     *
     * @return Type
     */
    public function type(): Type
    {
        return Type::Error;
    }

    /**
     * on success callback process
     *
     * @param callable $callback
     * @return Result<never,E>
     */
    public function onSuccess(callable $callback)
    {
        return new self($this->result);
    }

    /**
     * on error callback process
     *
     * @param callable(E):void $callback
     * @return static
     */
    public function onError(callable $callback)
    {
        call_user_func($callback, $this->error());

        return new self($this->result);
    }

    /**
     * map success
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback): Result
    {
        return new self($this->result);
    }

    /**
     * map error
     *
     * @template R
     *
     * @param callable(E):R $callback
     * @return Result<never,R>
     */
    public function mapError(callable $callback): Result
    {
        return new self($callback($this->error()));
    }

    /**
     * flat map success
     *
     * @param callable $callback
     * @return static
     */
    public function flatMap(callable $callback): Result
    {
        return new self($this->result);
    }

    /**
     * flat map error
     *
     * @template R
     * @template F
     *
     * @param callable(E):Result<R,F> $callback
     * @return Result<R,F>
     */
    public function flatMapError(callable $callback): Result
    {
        return $callback($this->error());
    }

    /**
     * map both process
     *
     * @template R
     *
     * @param callable|null $success
     * @param callable(E):R|null $error
     * @return Result<never,R>|static
     */
    public function mapBoth(
        ?callable $success = null,
        ?callable $error = null,
    ): Result {
        return $error ?
            new static(call_user_func($error, $this->error())) :
            new self($this->result);
    }

    /**
     * throw exception
     *
     * @return static
     * @throws Throwable
     */
    public function exception()
    {
        if ($e = $this->error()) {
            switch ($e) {
                case $e instanceof Throwable:
                    throw $e;
                case $e instanceof ErrorHandler:
                    throw $e->e;
            }
        }

        return new self($this->result);
    }

    /**
     * create a error result.
     *
     * @template R
     *
     * @param R $result
     *
     * @return Error<R>
     */
    public static function create($result = null)
    {
        return new self($result);
    }
}
