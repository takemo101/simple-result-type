<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Exception;
use LogicException;
use InvalidArgumentException;
use Takemo101\SimpleResultType\Support\ExceptionHandler;

/**
 * exception handler test
 */
class ExceptionHandlerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function handler__catch__OK(): void
    {
        $message = 'error';

        $handler = new ExceptionHandler(new Exception($message));
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

        $handler = new ExceptionHandler(new RuntimeException($message));
        $result = $handler
            ->catch(fn (RuntimeException $e) => $e->getMessage())
            ->catch(fn (Exception $e) => 'error')
            ->exception();

        $this->assertEquals($result, $message);
    }

    /**
     * @test
     *
     * @return void
     */
    public function handler__exception__NG(): void
    {
        $this->expectException(LogicException::class);

        $handler = new ExceptionHandler(new LogicException('error'));
        $handler
            ->catch(function (InvalidArgumentException $e) {
                return 'invalid error';
            })
            ->catch(function (RuntimeException $e) {
                return 'runtime error';
            })
            ->exception();
    }
}
