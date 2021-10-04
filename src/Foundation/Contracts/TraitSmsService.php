<?php

namespace ProgLib\Sms\Foundation\Contracts;

/**
 * Реализует часть правил реализации протоколов передачи СМС.
 */
trait TraitSmsService {

    #region Properties

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

    public function to(...$phone) {
        foreach ($phone as $item)
            $this->to[] = $item;

        return $this;
    }

    public function from($sender) {
        $this->sender = $sender;

        return $this;
    }

    abstract function send($message);
}