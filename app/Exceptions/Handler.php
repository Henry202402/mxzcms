<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Throwable;

class Handler extends ExceptionHandler {
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
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, $exception) {
        try {
            die(view(dealErrorExceptionInfo($exception)['view'], [
                'msg' => "statusCode:".dealErrorExceptionInfo($exception)['statusCode'].";".$exception->getMessage(),
                "file" => $exception->getFile(),
                "line" => $exception->getLine()
            ]));
        }catch (\Exception $e){
            dd($exception);
        }
    }

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
    public function register() {

        // 自定义
        $this->renderable(function (\Throwable $exception) {
            hook("ExceptionHandler", ['exception'=>$exception]);
        });

        $this->reportable(function (Throwable $e) {

        });
    }


}
