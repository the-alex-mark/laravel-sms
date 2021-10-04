<?php

namespace ProgLib\Sms\Foundation\Transports;

use Carbon\Carbon;
use ProgLib\Sms\Foundation\Contracts\ISmsService;
use ProgLib\Sms\Foundation\Contracts\TraitSmsService;
use ProgLib\Sms\Foundation\Helpers\Helper;

/**
 * Реализует сохранение передаваемого смс-сообщения в журнал.
 */
class LogTransport implements ISmsService {

    /**
     * Инициализирует новый экземпляр для отправки смс-сообщения в журнал.
     */
    public function __construct() {

        // Получение параметров конфигурации
        $this->channel = config('sms.logging.channel', 'sms');
        $this->sender  = config('sms.settings.log.sender');
    }

    #region Properties

    /**
     * Канал журнала.
     * @var string
     */
    protected $channel;

    #endregion

    #region Sales

    use TraitSmsService;

    public function send($message) {
        $date    = 'Date: ' . Carbon::now()->setTimezone(config('app.timezone'))->format('D, d M Y H:m:s O');
        $from    = 'From: ' . $this->sender;
        $to      = 'To: '   . implode(', ', $this->to);

        // Вывод в журнал
        Helper::log(PHP_EOL . implode(PHP_EOL, [ $date, $to, $from, $message ]) . PHP_EOL);
    }

    #endregion
}