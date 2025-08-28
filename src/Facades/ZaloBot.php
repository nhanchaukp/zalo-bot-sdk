<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Facades;

use Illuminate\Support\Facades\Facade;
use NhanChauKP\ZaloBotSdk\Services\ZaloBotManager;

/**
 * @method static \NhanChauKP\ZaloBotSdk\ZaloBot bot(?string $name = null)
 * @method static string getDefaultBot()
 * @method static \Illuminate\Support\Collection getBots()
 * @method static bool hasBot(string $name)
 * @method static array getBotNames()
 * @method static array sendMessage(string $chatId, string $text)
 * @method static array sendPhoto(string $chatId, string $photo, ?string $caption = null)
 * @method static array sendSticker(string $chatId, string $sticker)
 * @method static array getUserProfile(string $userId)
 * @method static array setWebhook(string $url)
 * @method static array deleteWebhook()
 * @method static array getWebhookInfo()
 * @method static \NhanChauKP\ZaloBotSdk\ZaloBot addCommand(string $command)
 * @method static \NhanChauKP\ZaloBotSdk\ZaloBot addCommands(array $commands)
 * @method static \NhanChauKP\ZaloBotSdk\ZaloBot removeCommand(string $command)
 * @method static \Illuminate\Support\Collection getCommands()
 * @method static mixed processUpdate(array $update)
 *
 * @see \NhanChauKP\ZaloBotSdk\Services\ZaloBotManager
 */
final class ZaloBot extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ZaloBotManager::class;
    }
}
