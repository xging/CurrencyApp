<?php
namespace App\Services\Console\CurrencyRateConsole;
use Exception;
class CommandHandlerService
{
    private CommandRegistryService $registry;

    public function __construct(CommandRegistryService $registry)
    {
        $this->registry = $registry;
    }

    public function handle(string $command, array $args): void
    {
        try {
            $this->registry->get($command)->execute($args);
        } catch (Exception $e) {
            echo "Error executing command '{$command}': " . $e->getMessage() . PHP_EOL;
        }
    }
}