<?php

namespace ProgLib\Sms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Сервис отправки СМС.
 */
class SMS extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'sms';
    }
}