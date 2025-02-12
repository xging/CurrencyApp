<?php
namespace App\Services\Console\CurrencyRateConsole;

use App\Services\Console\CurrencyRateConsole\Interfaces\CurrencyRateConsoleInterface;
class DefaultCommandService implements CurrencyRateConsoleInterface
{
    public function execute(array $args): void
    {
    $factory = new CommandFactoryService();
    $registry = $factory->createCommandRegistry($args);
    echo "Default command executed. No specific command was found.\n";
    echo "Available commands: " . implode(', ', $registry->getAll()) . PHP_EOL ."\n";
        
    }
}
