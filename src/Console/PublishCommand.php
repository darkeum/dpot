<?php

namespace Darkeum\Dpot\Console;

use Boot\System\Console\Command;

class PublishCommand extends Command
{
    /**
     * Имя и параметры консольной команды
     *
     * @var string
     */
    protected $signature = 'dpot:publish';

    /**
     * Описание консольной команды
     *
     * @var string
     */
    protected $description = 'Публикация Docker файла для Darklyy';

    /**
     * Выполнение консольной команды.
     *
     * @return void
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'dpot-docker']);

        file_put_contents(
            $this->laravel->basePath('docker-compose.yml'),
            str_replace(
                [
                    './vendor/darkeum/dpot/runtimes/8.1',
                ],
                [
                    './docker/8.1',
                ],
                file_get_contents($this->laravel->basePath('docker-compose.yml'))
            )
        );
    }
}
