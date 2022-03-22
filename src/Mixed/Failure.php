<?php

namespace Takemo101\SimpleResultType\Mixed;

use Takemo101\SimpleResultType\Failure as AbstractFailure;

/**
 * mixed failure
 *
 * @template S
 * @template F
 * @extends AbstractFailure<S,F>
 */
final class Failure extends AbstractFailure
{
    /**
     * constructor
     *
     * @param F $failure
     */
    public function __construct(
        private $failure,
    ) {
        //
    }

    /**
     * failure data
     *
     * @return F
     */
    public function failure()
    {
        return $this->failure;
    }
}
