<?php

namespace ProgLib\Sms\Providers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use ProgLib\Sms\Exceptions\SmsServiceHandler;
use ProgLib\Sms\Foundation\SmsService;

/**
 *
 */
class SmsServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides() {
        return [ 'sms' ];
    }

    /**
     * Boot service provider.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot() {

        // Публикация конфигурации
        $this->publishes([
            __DIR__ . '/../../configs/sms.php'  => config_path('sms.php'),
            __DIR__ . '/../../configs/smpp.php' => config_path('smpp.php')
        ]);

        // Настройка журнала для логирования результатов передачи СМС
        $this->app->make('config')->set(
            'logging.channels.sms', $this->app->make('config')->get('sms.logging.settings')
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

        // Слияние конфигурации
        $this->mergeConfigFrom(__DIR__ . '/../../configs/sms.php',  'sms');
        $this->mergeConfigFrom(__DIR__ . '/../../configs/smpp.php', 'smpp');

        // Регистрация пользовательского обработчика исключений
        $this->app->singleton(ExceptionHandler::class, SmsServiceHandler::class);

        // Регистрация фасада
        $this->app->bind('sms', function ($app) {
            $provider  = $app->make('config')->get('sms.default');
            $transport = $app->make('config')->get("sms.settings.$provider.transport");
            $sender    = $app->make('config')->get("sms.settings.$provider.sender");

            return new SmsService($transport, $sender);
        });
    }
}