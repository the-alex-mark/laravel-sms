<?php

namespace ProgLib\Sms\Foundation;

use Exception;
use ProgLib\Sms\Foundation\Contracts\ISmsService;
use ProgLib\Sms\Foundation\Contracts\TraitSmsService;
use ProgLib\Sms\Foundation\Exceptions\SmsServiceException;

/**
 * Реализует передачу смс-сообщений.
 */
class SmsService implements ISmsService {

    use TraitSmsService;

    /**
     * Инициализирует новый экземпляр для отправки смс-сообщений.
     *
     * @param  ISmsService $transport Реализация провайдера для отправки СМС.
     * @param  mixed       $sender    Имя отправителя.
     */
    public function __construct($transport, $sender) {

        // Получение параметров конфигурации
        $this->transport = new $transport();
        $this->to        = [];
        $this->sender    = $sender;
    }

    #region Properties

    /**
     * Провайдер отправителя.
     * @var ISmsService
     */
    private $transport;

    /**
     * Номер телефона.
     * @var array
     */
    private $to;

    /**
     * Имя отправителя.
     * @var string
     */
    private $sender;

    #endregion

    public function send($message) {

        // Валидация входящих параметров
        if (empty($this->to) || empty($message))
            throw new SmsServiceException([], 'Параметры передачи смс-сообщения указаны некорректно.', 500);

        // Отправка сообщения
        try { $this->transport->to(...$this->to)->from($this->sender)->send($message); }
        catch (Exception $e) {
            $params = [
                'sender'    => implode(', ', $this->to),
                'recipient' => $this->sender,
                'message'   => $message
            ];

            throw new SmsServiceException($params, $e);
        }
    }
}