<?php

namespace Takemo101\SimpleResultType\None;

use Takemo101\SimpleResultType\Success as AbstractSuccess;

/**
 * none success
 *
 * @template F
 * @extends AbstractSuccess<void, F>
 */
final class Success extends AbstractSuccess
{
    /**
     * success data
     *
     * @return void
     */
    public function success(): void
    {
        //
    }
}
