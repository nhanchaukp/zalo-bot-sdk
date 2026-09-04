<?php

declare(strict_types=1);

namespace App\ZaloBots\Commands;

use NhanChauKP\ZaloBotSdk\Commands\Command;
use NhanChauKP\ZaloBotSdk\ZaloBot;

/**
 * Example Start Command
 *
 * Copy this file to your app/ZaloBots/Commands directory
 * and register it in your zalo-bot config file.
 */
class StartCommand extends Command
{
    protected string $name = 'start';

    protected string $description = 'Khởi động bot và hiển thị lời chào';

    protected array $aliases = ['begin', 'hello'];

    public function handle(array $update, ZaloBot $bot): mixed
    {
        $userId = $this->getUserId($update);

        if (! $userId) {
            return null;
        }

        // Lấy thông tin user (nếu cần)
        try {
            $userProfile = $bot->getUserProfile($userId);
            $userName = $userProfile['name'] ?? 'Bạn';
        } catch (\Exception $e) {
            $userName = 'Bạn';
        }

        $message = "🎉 Xin chào {$userName}!\n\n";
        $message .= "Chào mừng bạn đến với Zalo Bot của chúng tôi.\n\n";
        $message .= "📋 Các lệnh có sẵn:\n";
        $message .= "• /help - Xem tất cả lệnh\n";
        $message .= "• /start - Khởi động lại bot\n";
        $message .= "• /info - Thông tin về bot\n\n";
        $message .= '💡 Gõ /help để xem danh sách đầy đủ các lệnh.';

        return $this->reply($update, $bot, $message);
    }
}
