<?php

namespace Takemo101\SimpleResultType\Support;

use Throwable;

/**
 * abstract catch type interface for trial processing
 */
interface Catchable
{
    /**
     * are exceptions included in the target
     *
     * @param Throwable $e
     * @return boolean
     */
    public function included(Throwable $e): bool;
}
