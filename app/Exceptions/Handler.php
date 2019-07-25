<?php

namespace App\Exceptions;

use App\Helpers\LogActivity;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException){
            if ($request->ajax()){
                return response()->json([
                    'message' => 'Record not found',
                ], 404);
            }
        }

//        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
//            flash($exception->getMessage(), 'danger');
//            return back();
//        }

        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            flash($exception->getMessage(), 'danger');
            LogActivity::addToLog(Auth::user()->name, $exception->getMessage(), 'User');
            return back();
        }

        return parent::render($request, $exception);

    }
}
