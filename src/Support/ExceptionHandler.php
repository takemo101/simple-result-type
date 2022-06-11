<?php

namespace Takemo101\SimpleResultType\Support;

use ReflectionFunction;
use Throwable;
use Closure;

/**
 * Process according to the exception type
 */
final class ExceptionHandler
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
     * throw exception or call callback
     *
     * @return mixed
     */
    public function exception(): mixed
    {
        foreach ($this->callbacks as $callback) {
            $comparer = new CallbackArgumentTypeComparer(new ReflectionFunction($callback));

            if ($comparer->equals($this->e)) {
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
