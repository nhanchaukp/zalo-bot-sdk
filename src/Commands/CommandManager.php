<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Commands;

use Illuminate\Support\Collection;
use NhanChauKP\ZaloBotSdk\Exceptions\ZaloBotException;
use NhanChauKP\ZaloBotSdk\Contracts\ZaloBotInterface;

final class CommandManager
{
    protected Collection $commands;

    public function __construct()
    {
        $this->commands = new Collection();
    }

    /**
     * Add a command.
     */
    public function addCommand(string $command): self
    {
        if (class_exists($command)) {
            $instance = app($command);
            
            if (!$instance instanceof Command) {
                throw new ZaloBotException("Command {$command} must extend Command class.");
            }
            
            $this->commands->put($instance->getName(), $instance);
        }

        return $this;
    }

    /**
     * Add multiple commands.
     */
    public function addCommands(array $commands): self
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }

        return $this;
    }

    /**
     * Remove a command.
     */
    public function removeCommand(string $name): self
    {
        $this->commands->forget($name);

        return $this;
    }

    /**
     * Get all commands.
     */
    public function getCommands(): Collection
    {
        return $this->commands;
    }

    /**
     * Get a command by name.
     */
    public function getCommand(string $name): ?Command
    {
        // Check by name first
        if ($this->commands->has($name)) {
            return $this->commands->get($name);
        }

        // Check by aliases
        foreach ($this->commands as $command) {
            if ($command->matches($name)) {
                return $command;
            }
        }

        return null;
    }

    /**
     * Process incoming update and execute matching command.
     */
    public function processUpdate(array $update, ZaloBotInterface $bot): mixed
    {
        $text = $this->extractCommandFromUpdate($update);
        
        if ($text === null || !str_starts_with($text, '/')) {
            return null; // Not a command
        }

        $commandName = $this->parseCommandName($text);
        $command = $this->getCommand($commandName);

        if ($command === null) {
            return null; // Command not found
        }

        try {
            return $command->handle($update, $bot);
        } catch (\Exception $e) {
            throw new ZaloBotException(
                "Error executing command '{$commandName}': " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Extract command text from update.
     */
    protected function extractCommandFromUpdate(array $update): ?string
    {
        return $update['message']['text'] ?? 
               $update['callback_query']['data'] ?? 
               null;
    }

    /**
     * Parse command name from text.
     */
    protected function parseCommandName(string $text): string
    {
        $text = ltrim($text, '/');
        $parts = explode(' ', $text);
        
        return strtolower($parts[0] ?? '');
    }

    /**
     * Check if text is a command.
     */
    public function isCommand(string $text): bool
    {
        return str_starts_with(trim($text), '/');
    }

    /**
     * Get command names.
     */
    public function getCommandNames(): array
    {
        return $this->commands->keys()->toArray();
    }

    /**
     * Check if a command exists.
     */
    public function hasCommand(string $name): bool
    {
        return $this->getCommand($name) !== null;
    }
}
