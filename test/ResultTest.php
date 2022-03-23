<?php

namespace Test;

use Exception;
use PHPUnit\Framework\TestCase;
use Takemo101\SimpleResultType\{
    Resulter,
    Type,
};

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
    public function resulter__trial__OK(): void
    {
        $data = Resulter::trial(function () {
            return 'string';
        })
            ->exception()
            ->success();

        $this->assertEquals($data, 'string');
    }

    /**
     * @test
     *
     * @return void
     */
    public function resulter__trial__NG(): void
    {
        $this->expectException(Exception::class);

        Resulter::trial(function () {
            throw new Exception('error');
        })
            ->exception()
            ->error();
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
}
