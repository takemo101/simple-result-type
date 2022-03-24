<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Takemo101\SimpleResultType\Resulter;
use Takemo101\SimpleResultType\Support\{
    CatchType,
    NotCatchType,
};
use RuntimeException;
use InvalidArgumentException;
use Exception;
use LogicException;

/**
 * resulter test
 */
class ResulterTest extends TestCase
{
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
    public function resulter__CatchType__OK(): void
    {
        $result = Resulter::trial(
            #[CatchType(Exception::class)]
            function () {
                throw new RuntimeException('error');
            }
        );

        $this->assertTrue($result->error() instanceof RuntimeException);
    }

    /**
     * @test
     *
     * @return void
     */
    public function resulter__CatchType__multi__OK(): void
    {
        $result = Resulter::trial(
            #[CatchType(
                RuntimeException::class,
                InvalidArgumentException::class,
            )]
            function () {
                throw new InvalidArgumentException('error');
            }
        );

        $this->assertTrue($result->error() instanceof InvalidArgumentException);
    }

    /**
     * @test
     *
     * @return void
     */
    public function resulter__CatchType__NG(): void
    {
        $this->expectException(Exception::class);

        Resulter::trial(
            #[CatchType(RuntimeException::class)]
            function () {
                throw new Exception('error');
            }
        );
    }

    /**
     * @test
     *
     * @return void
     */
    public function resulter__NotCatchType__OK(): void
    {
        $result = Resulter::trial(
            #[NotCatchType(
                InvalidArgumentException::class,
                LogicException::class,
            )]
            function () {
                throw new RuntimeException('error');
            }
        );

        $this->assertTrue($result->error() instanceof RuntimeException);
    }

    /**
     * @test
     *
     * @return void
     */
    public function resulter__NotCatchType__NG(): void
    {
        $this->expectException(RuntimeException::class);

        Resulter::trial(
            #[NotCatchType(RuntimeException::class)]
            function () {
                throw new RuntimeException('error');
            }
        );
    }
}
