<?php
namespace App\Services\Console\CurrencyRateConsole;
use App\Services\Console\CurrencyRateConsole\DefaultCommandService;
use App\Services\Console\CurrencyRateConsole\Interfaces\CurrencyRateConsoleInterface;
class CommandRegistryService
{
    private array $commands = [];
    private CurrencyRateConsoleInterface $defaultCommand;

    public function register(string $name, $command): void
    {
        $this->commands[$name] = $command;
        $this->defaultCommand = new DefaultCommandService;
        
    }
    public function get(string $name): CurrencyRateConsoleInterface
    {
        if (!isset($this->commands[$name])) {
            echo "Command '{$name}' not found. Returning default. \n";
            return $this->defaultCommand;
        }
        
        return $this->commands[$name];
    }

    public function getAll(): array
    {
        return array_keys($this->commands);
    }
}