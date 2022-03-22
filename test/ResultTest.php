<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleResultType\{
    Success,
    Failure,
    Result,
    Resulter,
};
use Takemo101\SimpleResultType\None\{
    Success as NoneSuccess,
};
use Takemo101\SimpleResultType\Mixed\{
    Success as MixedSuccess,
};
use Exception;

/**
 * result test
 */
class EntityTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function createSuccess__OK(): void
    {
        $success = new TestSuccess(10);

        $data = $success->success();

        $this->assertEquals($data, 10);

        $success
            ->onSuccess(function (int $success) use (&$data) {
                $data = 5;
            })
            ->onFailure(function (Exception $failure) use (&$data) {
                $data = 40;
            });

        $this->assertEquals($data, 5);

        $data = $success->on(
            onSuccess: fn () => 80,
            onFailure: fn () => 160,
        );

        $this->assertEquals($data, 80);
    }

    /**
     * @test
     *
     * @return void
     */
    public function createFailure__OK(): void
    {
        $this->expectException(Exception::class);

        $failure = new TestFailure(new Exception('error'));

        $data = $failure->failure()->getMessage();

        $this->assertEquals($data, 'error');

        $failure
            ->onSuccess(function (int $success) use (&$data) {
                $data = 'success';
            })
            ->onFailure(function (Exception $failure) use (&$data) {
                $data = 'failure';
            });

        $this->assertEquals($data, 'failure');

        $data = $failure->on(
            onSuccess: fn () => 'success',
            onFailure: fn () => 'failure error',
        );

        $this->assertEquals($data, 'failure error');

        $failure->exception();
    }

    /**
     * @test
     *
     * @return void
     */
    public function createNoneSuccess__OK(): void
    {
        $data = 10;
        $result = $this->createNoneSuccessResult();
        $result->onSuccess(function () use (&$data) {
            $data = 20;
        });

        $this->assertEquals($data, 20);
    }

    /**
     * @test
     *
     * @return void
     */
    public function createNoneSuccess__NG(): void
    {
        $this->expectException(Exception::class);

        $result = $this->createNoneSuccessExceptionResult();
        $result->exception();
    }

    /**
     * @test
     *
     * @return void
     */
    public function createMixedSuccess__NG(): void
    {
        $result = $this->createMixedSuccessResult();

        $this->assertEquals($result->success(), 10);
    }

    /**
     * @test
     *
     * @return void
     */
    public function createResult__OK(): void
    {
        $result = $this->createSuccessResult();

        $this->assertEquals($result->success(), 10);
    }

    /**
     * @test
     *
     * @return void
     */
    public function resulter__OK(): void
    {
        /**
         * @var Result<void, Exception>
         */
        $result = Resulter::watch(function () {
            return new NoneSuccess;
        });

        $this->assertTrue($result->isSuccess());
    }

    /**
     * @test
     *
     * @return void
     */
    public function resulterVoid__OK(): void
    {
        /**
         * @var Result<void, Exception>
         */
        $result = Resulter::watch(function () {
            //
        });

        $this->assertTrue($result->isSuccess());
    }

    /**
     * create success result
     *
     * @return Result<integer,Exception>
     */
    private function createSuccessResult(): Result
    {
        return new TestSuccess(10);
    }

    /**
     * create none success result
     *
     * @return Result<void,Exception>
     */
    private function createNoneSuccessResult(): Result
    {
        /** @var NoneSuccess<Exception> */
        $success = new NoneSuccess;

        return $success;
    }

    /**
     * create none success result
     *
     * @return Result<void,Exception>
     */
    private function createNoneSuccessExceptionResult(): Result
    {
        return new TestNoneFailure(new Exception('error'));
    }

    /**
     * create mixed success result
     *
     * @return Result<integer,Exception>
     */
    private function createMixedSuccessResult(): Result
    {
        return new MixedSuccess(10);
    }
}

/**
 * test success
 *
 * @extends Success<integer,Exception>
 */
class TestSuccess extends Success
{
    /**
     * constructor
     *
     * @param integer $success
     */
    public function __construct(private int $success)
    {
        //
    }

    /**
     * success data
     *
     * @return integer
     */
    public function success(): int
    {
        return $this->success;
    }
}


/**
 * test failure
 *
 * @extends Failure<integer,Exception>
 */
class TestFailure extends Failure
{
    public function __construct(
        private Exception $failure,
    ) {
        //
    }

    /**
     * failure data
     *
     * @return Exception
     */
    public function failure(): Exception
    {
        return $this->failure;
    }
}


/**
 * test none failure
 *
 * @extends Failure<void,Exception>
 */
class TestNoneFailure extends Failure
{
    public function __construct(
        private Exception $failure,
    ) {
        //
    }

    /**
     * failure data
     *
     * @return Exception
     */
    public function failure(): Exception
    {
        return $this->failure;
    }
}
