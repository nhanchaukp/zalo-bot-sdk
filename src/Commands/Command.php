<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Commands;

use NhanChauKP\ZaloBotSdk\Contracts\ZaloBotInterface;

abstract class Command
{
    /**
     * The name and signature of the command.
     */
    protected string $name = '';

    /**
     * The command description.
     */
    protected string $description = 'No description provided.';

    /**
     * Command aliases.
     */
    protected array $aliases = [];

    /**
     * Command arguments description.
     */
    protected array $arguments = [];

    /**
     * Execute the command.
     */
    abstract public function handle(array $update, ZaloBotInterface $bot): mixed;

    /**
     * Get the command name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the command description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Get command aliases.
     */
    public function getAliases(): array
    {
        return $this->aliases;
    }

    /**
     * Get command arguments.
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Check if the command matches the given name.
     */
    public function matches(string $name): bool
    {
        return $this->name === $name || in_array($name, $this->aliases, true);
    }

    /**
     * Get the chat ID from update.
     */
    protected function getChatId(array $update): ?string
    {
        return $update['message']['chat']['id'] ?? 
               $update['callback_query']['message']['chat']['id'] ?? 
               null;
    }

    /**
     * Get the user ID from update.
     */
    protected function getUserId(array $update): ?string
    {
        return $update['message']['from']['id'] ?? 
               $update['callback_query']['from']['id'] ?? 
               null;
    }

    /**
     * Get the message text from update.
     */
    protected function getMessageText(array $update): ?string
    {
        return $update['message']['text'] ?? 
               $update['callback_query']['data'] ?? 
               null;
    }

    /**
     * Get command arguments from message text.
     */
    protected function getCommandArguments(string $text): array
    {
        $parts = explode(' ', trim($text));
        
        // Remove the command name (first part)
        array_shift($parts);
        
        return array_filter($parts);
    }

    /**
     * Reply to the message.
     */
    protected function reply(array $update, ZaloBotInterface $bot, string $message): array
    {
        $chatId = $this->getChatId($update);
        
        if ($chatId === null) {
            throw new \InvalidArgumentException('Unable to determine chat ID from update.');
        }

        return $bot->sendMessage($chatId, $message);
    }
}
