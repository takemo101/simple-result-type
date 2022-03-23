<?php

namespace Takemo101\SimpleResultType;

enum Type: string
{
    case Success = 'success';
    case Error = 'error';

    /**
     * @return boolean
     */
    function isError(): bool
    {
        return $this == self::Error;
    }

    /**
     * @return boolean
     */
    function isSuccess(): bool
    {
        return $this == self::Success;
    }
}
