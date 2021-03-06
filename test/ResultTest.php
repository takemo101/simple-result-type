<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleResultType\{
    Resulter,
    Type,
    Error,
    Success,
};
use Takemo101\SimpleResultType\Support\CatchType;
use Exception;
use RuntimeException;
use InvalidArgumentException;
use LogicException;

/**
 * result test
 */
class ResultTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function success__onSuccess__OK(): void
    {
        $success = '';
        $data = Resulter::success('string')
            ->onSuccess(function () use (&$success) {
                $success = 'success';
            })
            ->success();

        $this->assertEquals($success, 'success');
        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function success__onError__OK(): void
    {
        $error = '';
        $data = Resulter::success('string')
            ->onError(function () use (&$error) {
                $error = 'error';
            })
            ->success();

        $this->assertEquals($error, '');
        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function success__map__OK(): void
    {
        $data = Resulter::success('string')
            ->map(function () {
                return 'first';
            })
            ->map(function () {
                return 'second';
            })
            ->success();

        $this->assertEquals($data, 'second');
    }

    /**
     * @test
     *
     * @return void
     */
    public function success__flatMap__OK(): void
    {
        $data = Resulter::success('string')
            ->flatMap(function () {
                return Resulter::success('first');
            })
            ->flatMap(function () {
                return Resulter::error('second');
            })
            ->error();

        $this->assertEquals($data, 'second');
    }

    /**
     * @test
     *
     * @return void
     */
    public function success__mapBoth__OK(): void
    {
        $data = Resulter::success('string')
            ->mapBoth(
                success: fn (string $result) => 'success',
                error: fn (string $result) => 'error'
            )
            ->success();

        $this->assertEquals($data, 'success');
    }

    /**
     * @test
     *
     * @return void
     */
    public function success__output__OK(): void
    {
        $data = Resulter::success('string')
            ->output(
                success: fn (string $result) => $result . '-success',
                error: fn (string $result) => $result . '-error',
            );

        $this->assertEquals($data, 'string-success');
    }

    /**
     * @test
     *
     * @return void
     */
    public function success__errorOr__OK(): void
    {
        $data = Resulter::success('string')
            ->errorOr('error');

        $this->assertEquals($data, 'error');
    }


    /**
     * @test
     *
     * @return void
     */
    public function success__successOr__OK(): void
    {
        $result = Resulter::success('string');

        $this->assertEquals($result->successOr('success'), 'string');

        $result = Resulter::error('string');

        $this->assertEquals($result->successOr(), null);
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__onSuccess__OK(): void
    {
        $success = '';
        $data = Resulter::error('string')
            ->onSuccess(function () use (&$success) {
                $success = 'success';
            })
            ->error();

        $this->assertEquals($success, '');
        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__onError__OK(): void
    {
        $error = '';
        $data = Resulter::error('string')
            ->onError(function () use (&$error) {
                $error = 'error';
            })
            ->error();

        $this->assertEquals($error, 'error');
        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__map__OK(): void
    {
        $data = Resulter::error('string')
            ->map(function () {
                return 'first';
            })
            ->map(function () {
                return 'second';
            })
            ->error();

        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__mapError__OK(): void
    {
        $data = Resulter::error('string')
            ->mapError(function () {
                return 'first';
            })
            ->mapError(function () {
                return 'second';
            })
            ->error();

        $this->assertEquals($data, 'second');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__flatMapError__OK(): void
    {
        $data = Resulter::error('string')
            ->flatMapError(function (string $result) {
                return Resulter::error('first-' . $result);
            })
            ->flatMapError(function (string $result) {
                return Resulter::success('yes-' . $result);
            })
            ->success();

        $this->assertEquals($data, 'yes-first-string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__mapBoth__OK(): void
    {
        $data = Resulter::error('string')
            ->mapBoth(
                success: fn (string $result) => 'success',
                error: fn (string $result) => 'error'
            )
            ->error();

        $this->assertEquals($data, 'error');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__flatMap__OK(): void
    {
        $data = Resulter::error('string')
            ->flatMap(function () {
                return Resulter::success('first');
            })
            ->flatMap(function () {
                return Resulter::error('second');
            })
            ->error();

        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__output__OK(): void
    {
        $data = Resulter::error('string')
            ->output(
                success: fn (string $result) => $result . '-success',
                error: fn (string $result) => $result . '-error',
            );

        $this->assertEquals($data, 'string-error');
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__errorOr__OK(): void
    {
        $result = Resulter::error('string');

        $this->assertEquals($result->errorOr('error'), 'string');

        $result = Resulter::success('string');

        $this->assertEquals($result->errorOr(), null);
    }


    /**
     * @test
     *
     * @return void
     */
    public function error__successOr__OK(): void
    {
        $data = Resulter::error('string')
            ->successOr('success');

        $this->assertEquals($data, 'success');
    }


    /**
     * @test
     *
     * @return void
     */
    public function error__exception__OK(): void
    {
        $this->expectException(Exception::class);

        Resulter::error(new Exception('error'))
            ->exception()
            ->error();
    }

    /**
     * @test
     *
     * @return void
     */
    public function error__exception__NG(): void
    {
        $data = Resulter::error('string')
            ->exception()
            ->error();

        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function type__OK(): void
    {
        $result = Resulter::success('string');
        $data = match ($result->type()) {
            Type::Success => $result->success(),
            Type::Error => $result->error(),
        };

        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function readmeTest(): void
    {
        $this->expectException(Exception::class);


        // Create a Success object with the Success method of the Resulter class.
        $data = Resulter::success(10)
            // Create a Result object with a new value by the map method.
            ->map(fn (int $result) => $result * 2)
            // Get the success result by the success method.
            ->success();

        // var_dump($data);


        // Create an Error object with the error method of the Resulter class.
        $data = Resulter::error(10)
            // Create an Error object with a new value by the mapError method.
            ->mapError(fn (int $result) => $result * 2)
            // Get the error result by the error method.
            ->error();

        // var_dump($data);


        // If you generate an Error with a value that implements Throwable,
        // an exception will be raised.
        Resulter::error(new Exception('error'))->exception();

        // You can get the output according to the result by the output method.
        $data = Resulter::success(10)
            ->map(fn (int $result) => $result * 2)
            ->output(
                success: fn (int $result) => $result * 100,
                error: fn (int $result) => $result * 1,
            );

        // var_dump($data);

        // You can also create objects from the Error and Success classes.
        $result = Error::create('error');

        // var_dump($result->isError());
        // var_dump($result->isSuccess());


        $result = Success::create('success');

        // You can also judge by Type enum.
        $data = match ($result->type()) {
            Type::Success => $result->success(),
            Type::Error => $result->error(),
        };

        // var_dump($data);

        // If an exception occurs, the result will be returned as Error.
        $result = Resulter::try(function () {
            throw new Exception('error');
        }); // Error<Exception>

        // By returning the success value, the result will be returned as Success.
        $result = Resulter::try(function () {
            return 10;
        }); // Success<integer>

        // No error is output except for the exception specified in the CatchType Attribute class.
        Resulter::try(
            #[CatchType(
                RuntimeException::class,
                InvalidArgumentException::class,
            )]
            function () {
                throw new LogicException('error');
            }
        );
    }
}
