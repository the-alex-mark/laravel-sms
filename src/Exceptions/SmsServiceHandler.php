<?php

namespace ProgLib\Sms\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use ProgLib\Sms\Foundation\Exceptions\SmsServiceException;

/**
 *
 */
class SmsServiceHandler extends ExceptionHandler {

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        SmsServiceException::class
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register() {

        // Обработка исключений при отправке СМС
        $this->renderable(function (SmsServiceException $e, $request) {
            $e->log();
        });
    }
}