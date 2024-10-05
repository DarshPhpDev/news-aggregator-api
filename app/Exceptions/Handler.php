<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use ApiResponse;

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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    
    // render function along with ApiReponse to handle api errors in json format
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
        
            // If not authenticated exception thrown
            return ApiResponse::sendResponse([], 401, null, true);
        
        } else if ($exception instanceof \Illuminate\Validation\ValidationException) {

            // If validataion exception thrown
            return ApiResponse::sendResponse([], 200, 'Validation Errors', true, $exception->validator->messages()->get('*'));
        
        }else{
        
            // Any other 500 server error
            return ApiResponse::sendResponse([], 500, $exception->getMessage(), true);
        
        }
        return parent::render($request, $exception);
    }
}
