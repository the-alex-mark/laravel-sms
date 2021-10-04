<?php

namespace ProgLib\Sms\Foundation\Contracts;

use ProgLib\Sms\Foundation\Exceptions\SmsServiceException;

/**
 * Задаёт правила реализации протоколов передачи СМС.
 */
interface ISmsService {

    /**
     * Задаёт номер телефона.
     *
     * @param  array $phone Номер телефона.
     * @return $this
     */
    public function to(...$phone);

    /**
     * Задаёт номер телефона.
     *
     * @param  string $sender Имя отправителя.
     * @return $this
     */
    public function from($sender);

    /**
     * Отправляет смс-сообщение на указанный номер телефона.
     *
     * @param  string $message Текст сообщения.
     * @return void
     * @throws SmsServiceException
     */
    public function send($message);
}