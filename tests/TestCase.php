<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Tests;

use NhanChauKP\ZaloBotSdk\ZaloBotServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Additional setup if needed
    }

    protected function getPackageProviders($app): array
    {
        return [
            ZaloBotServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('zalo-bot.default', 'test');
        $app['config']->set('zalo-bot.bots.test', [
            'token' => 'test-token',
            'webhook_url' => 'https://example.com/webhook',
            'commands' => [],
        ]);
        $app['config']->set('zalo-bot.base_bot_url', 'https://bot-api.zapps.me/bot');
    }

    protected function getPackageAliases($app): array
    {
        return [
            'ZaloBot' => \NhanChauKP\ZaloBotSdk\Facades\ZaloBot::class,
        ];
    }
}
