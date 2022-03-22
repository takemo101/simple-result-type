<?php

namespace Takemo101\SimpleResultType\Mixed;

use Takemo101\SimpleResultType\Success as AbstractSuccess;

/**
 * mixed success
 *
 * @template S
 * @template F
 * @extends AbstractSuccess<S,F>
 */
final class Success extends AbstractSuccess
{
    /**
     * constructor
     *
     * @param S $success
     */
    public function __construct(
        private $success,
    ) {
        //
    }

    /**
     * success data
     *
     * @return S
     */
    public function success()
    {
        return $this->success;
    }
}
