<?php

namespace ProgLib\Sms\Foundation\Transports;

use ProgLib\Sms\Foundation\Contracts\ISmsService;
use ProgLib\Sms\Foundation\Contracts\TraitSmsService;
use ProgLib\Sms\Foundation\Helpers\Helper;
use SMPP;
use SmppAddress;
use SmppClient;
use SmppException;
use SocketTransport;

if (!defined('MSG_DONTWAIT'))
    define('MSG_DONTWAIT', 0x20);

/**
 * Сервис отправки СМС по протоколу SMPP.
 */
class SmppTransport implements ISmsService {

    /**
     * Инициализирует новый экземпляр для отправки смс-сообщения по протоколу <b>SMPP</b>.
     */
    public function __construct() {

        // Получение параметров конфигурации
        $this->host       = config('sms.settings.smpp.host');
        $this->port       = config('sms.settings.smpp.port');
        $this->timeout    = config('sms.settings.smpp.timeout');
        $this->username   = config('sms.settings.smpp.username');
        $this->password   = config('sms.settings.smpp.password');
        $this->sender     = config('sms.settings.smpp.sender');

        // Установка параметров протокола
        SmppClient::$csms_method = SmppClient::CSMS_8BIT_UDH;
        SmppClient::$system_type = config('smpp.client.system_type', 'default');
        SmppClient::$sms_null_terminate_octetstrings = config('smpp.client.null_terminate_octetstrings', false);
        SocketTransport::$forceIpv4 = config('smpp.transport.force_ipv4', true);
        SocketTransport::$defaultDebug = config('smpp.transport.debug', false);
    }

    #region Properties

    /**
     * Сервер.
     * @var string
     */
    protected $host;

    /**
     * Номер порта.
     * @var numeric
     */
    protected $port;

    /**
     * Время ожидания ответа.
     * @var numeric
     */
    protected $timeout;

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

        // Инициализация экземпляра подключения к серверу
        $transport = new SocketTransport([ $this->host ], $this->port);

        // Установка параметров провайдера
        try {
            $transport->setRecvTimeout($this->timeout);
            $smpp = new SmppClient($transport);
            $smpp->debug = config('smpp.client.debug', false);
            $transport->open();
            $smpp->bindTransmitter($this->username, $this->password);
        }
        catch (SmppException $e) {
            $transport->close();

            // Вывод ошибки в случае неудачного подключения
            throw $e;
        }

        foreach ($this->to as $phone) {
            $sender    = $this->getSender();
            $recipient = $this->getRecipient($phone);
            $message   = mb_convert_encoding($message, 'UCS-2', 'utf8');

            // Отправка СМС
            $result = $smpp->sendSMS($sender, $recipient, $message, null, SMPP::DATA_CODING_UCS2);

            // Вывод в журнал
            Helper::log($result);
        }

        $smpp->close();
    }

    #endregion

    /**
     * Возвращает адрес отправителя.
     *
     * @return SmppAddress
     */
    protected function getSender() {
        return $this->getSmppAddress();
    }

    /**
     * Возвращает адрес получателя.
     *
     * @param $phone
     *
     * @return SmppAddress
     */
    protected function getRecipient($phone) {
        return $this->getSmppAddress($phone);
    }

    /**
     * Возвращает экземпляр <b>SmppAddress</b> на основе текущего отправителя.
     *
     * @param  mixed $phone
     * @return SmppAddress
     */
    protected function getSmppAddress($phone = null) {
        if ($phone === null)
            $phone = $this->sender;

        return new SmppAddress(
            $phone,
            hexdec(config('smpp.default.dest_addr_ton', 0x01)),
            hexdec(config('smpp.default.dest_addr_npi', 0x01))
        );
    }
}