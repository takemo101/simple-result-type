# The Simple Result Type

[![Testing](https://github.com/takemo101/simple-result-type/actions/workflows/testing.yml/badge.svg)](https://github.com/takemo101/simple-result-type/actions/workflows/testing.yml)
[![PHPStan](https://github.com/takemo101/simple-result-type/actions/workflows/phpstan.yml/badge.svg)](https://github.com/takemo101/simple-result-type/actions/workflows/phpstan.yml)
[![Validate Composer](https://github.com/takemo101/simple-result-type/actions/workflows/composer.yml/badge.svg)](https://github.com/takemo101/simple-result-type/actions/workflows/composer.yml)

The Simple Result Type simply returns the processing result as an object.  
Enjoy!  

## Example
This is a basic usage example.
```php
use Takemo101\SimpleResultType\ {
    Resulter,
    Error,
    Success,
};
use Exception;

// Create a Success object with the Success method of the Resulter class.
$data = Resulter::success(10)
    // Create a Result object with a new value by the map method.
    ->map(fn(int $result) => $result * 2)
    // Get the success result by the success method.
    ->success();

var_dump($data); // int(20)


// Create an Error object with the error method of the Resulter class.
$data = Resulter::error(10)
    // Create an Error object with a new value by the mapError method.
    ->mapError(fn(int $result) => $result * 2)
    // Get the error result by the error method.
    ->error();

var_dump($data); // int(20)


// If you generate an Error with a value that implements Throwable, 
// an exception will be raised.
Resulter::error(new Exception('error'))
    ->exception();


// You can get the output according to the result by the output method.
$data = Resulter::success(10)
    ->map(fn(int $result) => $result * 2)
    ->output(
        success: fn(int $result) => $result * 100,
        error: fn(int $result) => $result * 1,
    );

var_dump($data); // int(2000)
```
This is an example of using to determine whether the result is success or error.
```php
use Takemo101\SimpleResultType\ {
    Error,
    Success,
};

// You can also create objects from the Error and Success classes.
$result = Error::create('error');

var_dump($result->isError()); // bool(true)
var_dump($result->isSuccess()); // bool(false)


$result = Success::create('success');

// You can also judge by Type enum.
$data = match ($result->type()) {
    Type::Success => $result->success(),
    Type::Error => $result->error(),
};

var_dump($data); // string(7) "success"
```
Try operations that may fail and produce results.
```php
use Takemo101\SimpleResultType\Resulter;
use Takemo101\SimpleResultType\Support\ {
    CatchType,
    NotCatchType,
};
use Exception;
use LogicException;
use RuntimeException;
use InvalidArgumentException;

// If an exception occurs, the result will be returned as Error.
$result = Resulter::trial(function() {
    throw new Exception('error');
}); // Error<Exception>

// By returning the success value, the result will be returned as Success.
$result = Resulter::trial(function() {
    return 10;
}); // Success<integer>

// No error is output except for the exception specified in the CatchType Attribute class.
$result = Resulter::trial(
    #[CatchType(
        RuntimeException::class,
        InvalidArgumentException::class,
    )]
    function() {
        throw new RuntimeException('error');
    }
); // Error<RuntimeException>

var_dump($result->isError()); // bool(true)

// No error is output for the exception specified in the NotCatchType Attribute class.
Resulter::trial(
    #[NotCatchType(
        RuntimeException::class,
        InvalidArgumentException::class,
    )]
    function() {
        throw new RuntimeException('error');
    }
); // Exception occurs.
```
