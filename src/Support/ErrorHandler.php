<?php

namespace Takemo101\SimpleResultType\Support;

use Throwable;
use Closure;

/**
 * Process according to the exception type
 */
final class ErrorHandler
{
    /**
     * @var Closure[]
     */
    private $callbacks = [];

    /**
     * constructor
     *
     * @param Throwable $e
     */
    public function __construct(
        public readonly Throwable $e,
    ) {
        //
    }

    /**
     * call catch callbacks
     *
     * @return mixed
     */
    public function call(): mixed
    {
        foreach ($this->callbacks as $callback) {
            if (CallbackArgumentTypeComparer::compare($callback, $this->e)) {
                return $callback($this->e);
            }
        }

        return null;
    }

    /**
     * throw exception or call callback
     *
     * @return mixed
     */
    public function exception(): mixed
    {
        foreach ($this->callbacks as $callback) {
            if (CallbackArgumentTypeComparer::compare($callback, $this->e)) {
                return $callback($this->e);
            }
        }

        throw $this->e;
    }

    /**
     * add exception catch callback
     *
     * @param Closure $callback
     * @return self
     */
    public function catch(Closure $callback): self
    {
        $this->callbacks[] = $callback;

        return $this;
    }
}
