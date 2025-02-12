<?php

namespace App\Services\Console;

use App\Services\Console\CurrencyRateConsole\CommandFactoryService;
use App\Services\Console\CurrencyRateConsole\CommandHandlerService;

class ConsoleKernel
{
    public function handle(array $argv): void
    {
        $command = $argv[1] ?? null;
        $args = array_slice($argv, 2);

        $factory = new CommandFactoryService();
        $registry = $factory->createCommandRegistry($args);

        $handler = new CommandHandlerService($registry);
        $handler->handle($command, $args);
    }
}