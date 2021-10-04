<?php

namespace ProgLib\Sms\Foundation\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Исключение передачи СМС.
 */
class SmsServiceException extends Exception {

    public function __construct($params = [], $message = '', $code = 0, $previous = null) {
        if (func_get_args()[1] instanceof Exception) {
            $this->__construct1($params, func_get_args()[1]);
        }
        else {
            parent::__construct($message, $code, $previous);
        }

        // Установка параметров отправки СМС
        $this->sms_sender    = $params['sender']    ?? '';
        $this->sms_recipient = $params['recipient'] ?? '';
        $this->sms_message   = $params['message']   ?? '';
    }

    public function __construct1($params = [], $exception = null) {
        $this->code    = $exception->getCode();
        $this->message = $exception->getMessage();
        $this->file    = $exception->getFile();
        $this->line    = $exception->getLine();
    }

    #region Properties

    protected static $channel = 'sms';

    private $sms_sender;
    private $sms_recipient;
    private $sms_message;

    #endregion

    /**
     * Выводит в журнал сообщение о текущей ошибке.
     *
     * @return void
     */
    public function log() {
        $message = json_encode([
            'params'      => [
                'from'    => $this->sms_recipient,
                'to'      => $this->sms_sender,
                'message' => $this->sms_message,
            ],
            'error'       => [
                'code'    => $this->getCode(),
                'message' => $this->getMessage(),
                'file'    => $this->getFile(),
                'line'    => $this->getLine()
            ]
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_FORCE_OBJECT);

        // Вывод в журнал
        $channel   = config('sms.logging.channel', 'sms');
        $separator = str_repeat('-', 100);

        // Поддержка ранних версий «Laravel»
        if (method_exists(app('log'), 'channel')) {
            Log::channel($channel)->error($message);
            Log::channel($channel)->error($separator);
        }
        else {
            Log::error($message);
            Log::error($separator);
        }
    }
}