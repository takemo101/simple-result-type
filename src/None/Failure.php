<?php

namespace Takemo101\SimpleResultType\None;

use Takemo101\SimpleResultType\Failure as AbstractFailure;

/**
 * none failure
 *
 * @template S
 * @extends AbstractFailure<S,void>
 */
final class Failure extends AbstractFailure
{
    /**
     * failure data
     *
     * @return void
     */
    public function failure(): void
    {
        //
    }
}
