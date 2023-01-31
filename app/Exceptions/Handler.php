<?php

namespace App\Exceptions;
use App\Exceptions\CustomException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Mockery\Exception\InvalidOrderException;
use Throwable;
use Spatie\Permission\Exceptions\UnauthorizedException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    protected function context()
{
    return array_merge(parent::context(), [
        'foo' => 'bar',
    ]);
}
    public function register()
    {       
        /* return 500;
        $this->reportable(function (Throwable $e) {
            return $e->getMessage();
        }); */
     
            $this->renderable(function (InvalidOrderException $e, $request) {
                return response()->view('errors.invalid-order', [], 500);
            });

/* 
        $this->renderable(function (UnauthorizedException $e, $request) {
            return $e->getMessage();
            return response()->json([
                'responseMessage' => 'You do not have the required authorization.',
                'responseStatus'  => 403,
            ]);
        }); */
    } 


}
