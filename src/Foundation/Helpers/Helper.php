<?php

namespace ProgLib\Sms\Foundation\Helpers;

use DOMDocument;
use Illuminate\Support\Facades\Log;

/**
 * Вспомогательный функционал.
 */
class Helper {

    /**
     * Форматирует строку формата <b>XML</b> в читабельный вид.
     *
     * @param  string $value
     * @return false|string
     */
    public static function xml_pretty($value) {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($value);

        return $dom->saveXML();
    }

    /**
     * Выводит в журнал отладочное сообщение.
     *
     * @param  string $message
     * @return void
     */
    public static function log($message, $channel = 'sms') {
        $channel   = config('sms.logging.channel', $channel);
        $separator = str_repeat('-', 100);

        // Поддержка ранних версий «Laravel»
        if (method_exists(app('log'), 'channel')) {
            Log::channel($channel)->debug($message);
            Log::channel($channel)->debug($separator);
        }
        else {
            Log::debug($message);
            Log::debug($separator);
        }
    }
}