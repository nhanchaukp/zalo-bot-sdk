<?php

/**
 * Example test script for Zalo Bot SDK
 * 
 * This file demonstrates basic usage of the Zalo Bot SDK
 */

require_once __DIR__ . '/../vendor/autoload.php';

use NhanChauKP\ZaloBotSdk\ZaloBot;
use NhanChauKP\ZaloBotSdk\Http\GuzzleHttpClient;

// Initialize bot with token
$token = 'YOUR_ZALO_BOT_TOKEN';
$bot = new ZaloBot($token, [
    'base_bot_url' => 'https://bot-api.zapps.me/bot'
]);

// Example: Send a simple message
try {
    $chatId = 'CHAT_ID_HERE'; // Replace with actual chat ID
    
    $result = $bot->sendMessage($chatId, 'Hello from Zalo Bot SDK! 🎉');
    echo "Message sent successfully!\n";
    print_r($result);
    
} catch (Exception $e) {
    echo "Error sending message: " . $e->getMessage() . "\n";
}

// Example: Send a photo
try {
    $photoUrl = 'https://example.com/image.jpg';
    $caption = 'This is a test photo from Zalo Bot SDK';
    
    $result = $bot->sendPhoto($chatId, $photoUrl, $caption);
    echo "Photo sent successfully!\n";
    print_r($result);
    
} catch (Exception $e) {
    echo "Error sending photo: " . $e->getMessage() . "\n";
}

// Example: Get user profile
try {
    $userId = 'USER_ID_HERE'; // Replace with actual user ID
    
    $profile = $bot->getUserProfile($userId);
    echo "User profile retrieved successfully!\n";
    print_r($profile);
    
} catch (Exception $e) {
    echo "Error getting user profile: " . $e->getMessage() . "\n";
}

// Example: Set webhook
try {
    $webhookUrl = 'https://yourapp.com/webhook/zalo';
    
    $result = $bot->setWebhook($webhookUrl);
    echo "Webhook set successfully!\n";
    print_r($result);
    
} catch (Exception $e) {
    echo "Error setting webhook: " . $e->getMessage() . "\n";
}

// Example: Get webhook info
try {
    $webhookInfo = $bot->getWebhookInfo();
    echo "Webhook info retrieved successfully!\n";
    print_r($webhookInfo);
    
} catch (Exception $e) {
    echo "Error getting webhook info: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n";
