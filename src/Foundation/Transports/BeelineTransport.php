<?php

namespace ProgLib\Sms\Foundation\Transports;

use BEESMS;
use ProgLib\Sms\Foundation\Contracts\ISmsService;
use ProgLib\Sms\Foundation\Contracts\TraitSmsService;
use ProgLib\Sms\Foundation\Helpers\Helper;

/**
 * Сервис отправки СМС провайдера <b>Beeline</b> по протоколу HTTP.
 */
class BeelineTransport implements ISmsService {

    /**
     * Инициализирует новый экземпляр для отправки смс-сообщения через провайдер «Beeline».
     */
    public function __construct() {

        // Получение параметров конфигурации
        $this->username = config('sms.settings.beeline.username');
        $this->password = config('sms.settings.beeline.password');
        $this->sender   = config('sms.settings.beeline.sender');
    }

    #region Properties

    /**
     * Логин.
     * @var string
     */
    protected $username;

    /**
     * Пароль.
     * @var string
     */
    protected $password;

    #endregion

    #region Sales

    use TraitSmsService;

    public function send($message) {
        $provider = (new BEESMS($this->username, $this->password));
        $result   = $provider->post_message($message, implode(', ', $this->to), $this->sender);

        // Вывод в журнал
        Helper::log(PHP_EOL . Helper::xml_pretty($result));
    }

    #endregion
}