<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use NhanChauKP\ZaloBotSdk\Facades\ZaloBot;

/**
 * Example Webhook Controller
 * 
 * Copy this file to your app/Http/Controllers directory
 * and add the route in your routes file.
 */
class ZaloWebhookController extends Controller
{
    /**
     * Handle incoming webhook from Zalo
     */
    public function handle(Request $request): JsonResponse
    {
        try {
            $update = $request->all();
            
            // Log incoming update for debugging
            Log::info('Zalo webhook received', ['update' => $update]);
            
            // Process the update
            $result = ZaloBot::processUpdate($update);
            
            // Log the result
            if ($result !== null) {
                Log::info('Zalo command processed', ['result' => $result]);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook processed successfully'
            ]);
            
        } catch (\Exception $e) {
            // Log error
            Log::error('Zalo webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'update' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }
    
    /**
     * Handle webhook for specific bot
     */
    public function handleBot(Request $request, string $botName): JsonResponse
    {
        try {
            $update = $request->all();
            
            // Process with specific bot
            $result = ZaloBot::bot($botName)->processUpdate($update);
            
            return response()->json([
                'status' => 'success',
                'bot' => $botName,
                'message' => 'Webhook processed successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Zalo webhook error for bot: ' . $botName, [
                'error' => $e->getMessage(),
                'update' => $request->all()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }
    
    /**
     * Set webhook URL
     */
    public function setWebhook(Request $request): JsonResponse
    {
        $request->validate([
            'url' => 'required|url',
            'bot' => 'nullable|string'
        ]);
        
        try {
            $bot = $request->input('bot') ? ZaloBot::bot($request->input('bot')) : ZaloBot::bot();
            $result = $bot->setWebhook($request->input('url'));
            
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook set successfully',
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Delete webhook
     */
    public function deleteWebhook(Request $request): JsonResponse
    {
        try {
            $bot = $request->input('bot') ? ZaloBot::bot($request->input('bot')) : ZaloBot::bot();
            $result = $bot->deleteWebhook();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Webhook deleted successfully',
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Get webhook info
     */
    public function getWebhookInfo(Request $request): JsonResponse
    {
        try {
            $bot = $request->input('bot') ? ZaloBot::bot($request->input('bot')) : ZaloBot::bot();
            $result = $bot->getWebhookInfo();
            
            return response()->json([
                'status' => 'success',
                'result' => $result
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
