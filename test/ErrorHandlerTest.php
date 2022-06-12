<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Exception;
use LogicException;
use InvalidArgumentException;
use Takemo101\SimpleResultType\Support\ErrorHandler;

/**
 * exception handler test
 */
class ErrorHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function handler__catch__OK(): void
    {
        $message = 'error';

        $handler = new ErrorHandler(new Exception($message));
        $result = $handler
            ->catch(function (Exception $e) {
                return $e->getMessage();
            })
            ->catch(function (RuntimeException $e) {
                return 'runtime error';
            })
            ->exception();

        $this->assertEquals($result, $message);

        $message = 'runtime error';

        $handler = new ErrorHandler(new RuntimeException($message));
        $result = $handler
            ->catch(fn (RuntimeException $e) => $e->getMessage())
            ->catch(fn (Exception $e) => 'error')
            ->exception();

        $this->assertEquals($result, $message);

        $handler = new ErrorHandler(new RuntimeException($message));
        $result = $handler
            ->catch(fn (RuntimeException $e) => $e->getMessage())
            ->call();

        $this->assertEquals($result, $message);

        $handler = new ErrorHandler(new LogicException($message));
        $result = $handler
            ->catch(fn (RuntimeException $e) => $e->getMessage())
            ->call();

        $this->assertNull($result);
    }

    /**
     * @test
     *
     * @return void
     */
    public function handler__exception__NG(): void
    {
        $this->expectException(LogicException::class);

        $handler = new ErrorHandler(new LogicException('error'));
        $handler
            ->catch(function (InvalidArgumentException $e) {
                return 'invalid error';
            })
            ->catch(function (RuntimeException $e) {
                return 'runtime error';
            })
            ->exception();
    }


    /**
     * @test
     *
     * @return void
     */
    public function handler__clear__OK(): void
    {
        $message = 'error';

        $handler = new ErrorHandler(new Exception($message));
        $handler = $handler
            ->catch(function (Exception $e) {
                return $e->getMessage();
            });

        $result = $handler->call();

        $this->assertEquals($result, $message);

        $result = $handler
            ->clear()
            ->call();

        $this->assertNull($result);
    }
}
