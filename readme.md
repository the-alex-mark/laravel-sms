# Laravel SMS

Реализует передачу СМС по протоколам `Log`, `SMPP` и `Beeline HTTP`.

<br>

## Установка

```bash
composer require the_alex_mark/laravel-sms
```

<br>

Публикация конфигурации:
```bash
php artisan vendor:publish --provider="ProgLib\Sms\Providers\SmsServiceProvider"
```

<br>

## Использование

<br>

Установка параметров конфигурации:
```ini
SMS_TRANSPORT=smpp
SMS_HOST=smpp.beeline.amega-inform.ru
SMS_PORT=8077
SMS_USERNAME=login
SMS_PASSWORD=password
```

<br>

Отправка письма при помощи фасада `SMS`:
```php
SMS::to('+7 (800) 00-00-01', '+7 (800) 000-00-02')
    ->from('ORG_NAME')
    ->send('Hello World!');
```