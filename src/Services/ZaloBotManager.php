<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Collection;
use NhanChauKP\ZaloBotSdk\Contracts\HttpClientInterface;
use NhanChauKP\ZaloBotSdk\Exceptions\ZaloBotException;
use NhanChauKP\ZaloBotSdk\Http\GuzzleHttpClient;
use NhanChauKP\ZaloBotSdk\ZaloBot;

final class ZaloBotManager
{
    protected Application $app;
    protected Collection $bots;
    protected array $config;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->bots = new Collection();
        $this->config = $app['config']['zalo-bot'] ?? [];
    }

    /**
     * Get a bot instance
     */
    public function bot(?string $name = null): ZaloBot
    {
        $name = $name ?: $this->getDefaultBot();

        if ($this->bots->has($name)) {
            return $this->bots->get($name);
        }

        return $this->bots[$name] = $this->createBot($name);
    }

    /**
     * Get the default bot
     */
    public function getDefaultBot(): string
    {
        return $this->config['default'] ?? 'default';
    }

    /**
     * Create a new bot instance
     */
    protected function createBot(string $name): ZaloBot
    {
        $config = $this->getBotConfig($name);

        $httpClient = $this->createHttpClient();

        $bot = new ZaloBot(
            $config['token'],
            array_merge($this->config, $config),
            $httpClient
        );

        // Register commands
        if (isset($config['commands'])) {
            $bot->addCommands($config['commands']);
        }

        // Register global commands
        if (isset($this->config['commands'])) {
            $bot->addCommands($this->config['commands']);
        }

        return $bot;
    }

    /**
     * Get bot configuration
     */
    protected function getBotConfig(string $name): array
    {
        $bots = $this->config['bots'] ?? [];

        if (!isset($bots[$name])) {
            throw new ZaloBotException("Bot configuration for '{$name}' not found.");
        }

        $config = $bots[$name];

        if (empty($config['token'])) {
            throw new ZaloBotException("Bot token for '{$name}' is not configured.");
        }

        return $config;
    }

    /**
     * Create HTTP client instance
     */
    protected function createHttpClient(): HttpClientInterface
    {
        $handler = $this->config['http_client_handler'] ?? null;

        if ($handler !== null && $handler instanceof HttpClientInterface) {
            return $handler;
        }

        return new GuzzleHttpClient([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * Get all configured bots
     */
    public function getBots(): Collection
    {
        return $this->bots;
    }

    /**
     * Check if a bot exists
     */
    public function hasBot(string $name): bool
    {
        return isset($this->config['bots'][$name]);
    }

    /**
     * Get all bot names
     */
    public function getBotNames(): array
    {
        return array_keys($this->config['bots'] ?? []);
    }

    /**
     * Dynamically call methods on the default bot
     */
    public function __call(string $method, array $parameters): mixed
    {
        return $this->bot()->$method(...$parameters);
    }
}
