<?php

namespace Darkeum\Dpot;

use Illuminate\Contracts\Support\DeferrableProvider;
use Boot\Support\ServiceProvider;
use Darkeum\Dpot\Console\InstallCommand;
use Darkeum\Dpot\Console\PublishCommand;

class DpotServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Запуск сервис провайдера
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
        $this->configurePublishing();
    }

    /**
     * Регистрация команд
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
                PublishCommand::class,
            ]);
        }
    }

    /**
     * Настройка публикации для пакета.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../runtimes' => $this->app->basePath('docker'),
            ], ['sail', 'sail-docker']);

            $this->publishes([
                __DIR__ . '/../bin/sail' => $this->app->basePath('sail'),
            ], ['sail', 'sail-bin']);
        }
    }

    /**
     * Получение услуг, предоставляемые провайдером.
     *
     * @return array
     */
    public function provides()
    {
        return [
            InstallCommand::class,
            PublishCommand::class,
        ];
    }
}
