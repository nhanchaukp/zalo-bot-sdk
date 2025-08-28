<?php

/**
 * Example routes for Zalo Bot SDK
 * 
 * Add these routes to your routes/web.php or routes/api.php file
 */

use App\Http\Controllers\ZaloWebhookController;
use Illuminate\Support\Facades\Route;

// Main webhook endpoint
Route::post('/webhook/zalo', [ZaloWebhookController::class, 'handle'])->name('zalo.webhook');

// Webhook for specific bot
Route::post('/webhook/zalo/{botName}', [ZaloWebhookController::class, 'handleBot'])->name('zalo.webhook.bot');

// Webhook management routes (optional - for admin panel)
Route::prefix('api/zalo')->group(function () {
    Route::post('/webhook/set', [ZaloWebhookController::class, 'setWebhook'])->name('zalo.webhook.set');
    Route::delete('/webhook', [ZaloWebhookController::class, 'deleteWebhook'])->name('zalo.webhook.delete');
    Route::get('/webhook/info', [ZaloWebhookController::class, 'getWebhookInfo'])->name('zalo.webhook.info');
});

// Example usage routes (for testing)
Route::prefix('api/zalo/test')->group(function () {
    Route::post('/send-message', function () {
        $chatId = request('chat_id');
        $text = request('text', 'Test message from Zalo Bot SDK');
        
        $result = \NhanChauKP\ZaloBotSdk\Facades\ZaloBot::sendMessage($chatId, $text);
        
        return response()->json($result);
    });
    
    Route::post('/send-photo', function () {
        $chatId = request('chat_id');
        $photo = request('photo');
        $caption = request('caption');
        
        $result = \NhanChauKP\ZaloBotSdk\Facades\ZaloBot::sendPhoto($chatId, $photo, $caption);
        
        return response()->json($result);
    });
    
    Route::post('/send-sticker', function () {
        $chatId = request('chat_id');
        $sticker = request('sticker');
        
        $result = \NhanChauKP\ZaloBotSdk\Facades\ZaloBot::sendSticker($chatId, $sticker);
        
        return response()->json($result);
    });

    Route::post('/chat-action', function () {
        $chatId = request('chat_id');
        $action = request('action'); // typing | upload_photo
        
        $enum = match ($action) {
            'upload_photo' => \NhanChauKP\ZaloBotSdk\Enums\ChatAction::UploadPhoto,
            default => \NhanChauKP\ZaloBotSdk\Enums\ChatAction::Typing,
        };
        
        $result = \NhanChauKP\ZaloBotSdk\Facades\ZaloBot::sendChatAction($chatId, $enum);
        
        return response()->json($result);
    });
    
    Route::get('/commands', function () {
        $commands = \NhanChauKP\ZaloBotSdk\Facades\ZaloBot::getCommands();
        
        return response()->json([
            'commands' => $commands->map(function ($command) {
                return [
                    'name' => $command->getName(),
                    'description' => $command->getDescription(),
                    'aliases' => $command->getAliases(),
                ];
            })
        ]);
    });
});
