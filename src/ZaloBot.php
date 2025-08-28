<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk;

use Illuminate\Support\Collection;
use NhanChauKP\ZaloBotSdk\Commands\CommandManager;
use NhanChauKP\ZaloBotSdk\Contracts\HttpClientInterface;
use NhanChauKP\ZaloBotSdk\Contracts\ZaloBotInterface;
use NhanChauKP\ZaloBotSdk\Enums\ChatAction;
use NhanChauKP\ZaloBotSdk\Exceptions\ZaloBotException;
use NhanChauKP\ZaloBotSdk\Http\GuzzleHttpClient;

final class ZaloBot implements ZaloBotInterface
{
    protected string $token;
    protected string $baseUrl;
    protected ?HttpClientInterface $httpClient;
    protected CommandManager $commandManager;
    protected array $config;

    public function __construct(
        string $token,
        array $config = [],
        ?HttpClientInterface $httpClient = null
    ) {
        $this->token = $token;
        $this->config = $config;
        $this->baseUrl = $config['base_bot_url'] ?? 'https://bot-api.zapps.me/bot';
        $this->httpClient = $httpClient ?? new GuzzleHttpClient();
        $this->commandManager = new CommandManager();
    }

    /**
     * Get the bot token
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * Get the base URL
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * Get the HTTP client
     */
    public function getHttpClient(): HttpClientInterface
    {
        return $this->httpClient;
    }

    /**
     * Get the command manager
     */
    public function getCommandManager(): CommandManager
    {
        return $this->commandManager;
    }

    /**
     * Send a text message
     */
    public function sendMessage(string $chatId, string $text): array
    {
        return $this->makeRequest('sendMessage', [
            'chat_id' => $chatId,
            'text' => $text,
        ]);
    }

    /**
     * Send a photo
     */
    public function sendPhoto(string $chatId, string $photo, ?string $caption = null): array
    {
        $params = [
            'chat_id' => $chatId,
            'photo' => $photo,
        ];

        if ($caption !== null) {
            $params['caption'] = $caption;
        }

        return $this->makeRequest('sendPhoto', $params);
    }

    /**
     * Send a sticker
     */
    public function sendSticker(string $chatId, string $sticker): array
    {
        return $this->makeRequest('sendSticker', [
            'chat_id' => $chatId,
            'sticker' => $sticker,
        ]);
    }

    /**
     * Send chat action (typing, upload_photo)
     */
    public function sendChatAction(string $chatId, ChatAction $action): array
    {
        return $this->makeRequest('sendChatAction', [
            'chat_id' => $chatId,
            'action' => $action->value,
        ]);
    }

    /**
     * Set webhook
     */
    public function setWebhook(string $url): array
    {
        return $this->makeRequest('setWebhook', [
            'url' => $url,
        ]);
    }

    /**
     * Delete webhook
     */
    public function deleteWebhook(): array
    {
        return $this->makeRequest('deleteWebhook');
    }

    /**
     * Get webhook info
     */
    public function getWebhookInfo(): array
    {
        return $this->makeRequest('getWebhookInfo');
    }

    /**
     * Add a command
     */
    public function addCommand(string $command): self
    {
        $this->commandManager->addCommand($command);

        return $this;
    }

    /**
     * Add multiple commands
     */
    public function addCommands(array $commands): self
    {
        $this->commandManager->addCommands($commands);

        return $this;
    }

    /**
     * Remove a command
     */
    public function removeCommand(string $command): self
    {
        $this->commandManager->removeCommand($command);

        return $this;
    }

    /**
     * Get all commands
     */
    public function getCommands(): Collection
    {
        return $this->commandManager->getCommands();
    }

    /**
     * Process incoming update
     */
    public function processUpdate(array $update): mixed
    {
        return $this->commandManager->processUpdate($update, $this);
    }

    /**
     * Make API request
     */
    protected function makeRequest(string $method, array $params = []): array
    {
        $url = $this->baseUrl . $this->token . '/' . $method;

        $headers = [
            'Content-Type' => 'application/json'
        ];

        try {
            return $this->httpClient->post($url, $params, $headers);
        } catch (ZaloBotException $e) {
            throw new ZaloBotException(
                "Zalo Bot API request failed for method '{$method}': " . $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }
}
