<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk;

use Illuminate\Support\ServiceProvider;
use NhanChauKP\ZaloBotSdk\Services\ZaloBotManager;

final class ZaloBotServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/zalo-bot.php',
            'zalo-bot'
        );

        $this->app->singleton('zalo-bot', function ($app) {
            return new ZaloBotManager($app);
        });

        $this->app->singleton(ZaloBotManager::class, function ($app) {
            return new ZaloBotManager($app);
        });

        $this->app->alias('zalo-bot', ZaloBotManager::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/zalo-bot.php' => config_path('zalo-bot.php'),
        ], 'zalo-bot-config');

        $this->publishes([
            __DIR__.'/../config/zalo-bot.php' => config_path('zalo-bot.php'),
        ], 'config');
    }

    public function provides(): array
    {
        return [
            'zalo-bot',
            ZaloBotManager::class,
        ];
    }
}
