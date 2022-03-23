<?php

namespace Takemo101\SimpleResultType;

/**
 * abstract result class
 *
 * @template S
 * @template E
 * @implements Result<S,E>
 */
abstract class AbstractResult implements Result
{
    /**
     * result is success
     *
     * @return boolean
     */
    final public function isSuccess(): bool
    {
        return $this->type()->isSuccess();
    }

    /**
     * result is error
     *
     * @return boolean
     */
    final public function isError(): bool
    {
        return $this->type()->isSuccess();
    }

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
    ): mixed {
        return match ($this->type()) {
            Type::Success => $success ? call_user_func($success, $this->success()) : $this->success(),
            Type::Error => $error ? call_user_func($error, $this->error()) : $this->error(),
        };
    }
}
