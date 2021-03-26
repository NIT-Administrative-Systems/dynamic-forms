<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        if (app()->bound('sentry')) {
            $this->reportable(function (Throwable $e) {
                app('sentry')->captureException($e);
            });
        }

        // For non-prod environments, display a special error page w/ explanation when the DB is asleep.
        if (config('app.env') !== 'production') {
            $this->renderable(function (QueryException $e, $request) {
                if (str_contains($e->getMessage(), 'timeout expired')) {
                    return response()->view('errors.database-paused', [], 500);
                }
            });
        }
    }
}
