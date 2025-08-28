<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Tests\Unit;

use NhanChauKP\ZaloBotSdk\Commands\CommandManager;
use NhanChauKP\ZaloBotSdk\Contracts\HttpClientInterface;
use NhanChauKP\ZaloBotSdk\Tests\TestCase;
use NhanChauKP\ZaloBotSdk\ZaloBot;
use PHPUnit\Framework\Attributes\Test;

class ZaloBotTest extends TestCase
{
    private ZaloBot $bot;
    private HttpClientInterface $mockHttpClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHttpClient = $this->createMock(HttpClientInterface::class);
        $this->bot = new ZaloBot('test-token', [
            'base_bot_url' => 'https://bot-api.zapps.me/bot'
        ], $this->mockHttpClient);
    }

    #[Test]
    public function it_can_be_instantiated(): void
    {
        $this->assertInstanceOf(ZaloBot::class, $this->bot);
    }

    #[Test]
    public function it_returns_correct_token(): void
    {
        $this->assertEquals('test-token', $this->bot->getToken());
    }

    #[Test]
    public function it_returns_correct_base_url(): void
    {
        $this->assertEquals('https://bot-api.zapps.me/bot', $this->bot->getBaseUrl());
    }

    #[Test]
    public function it_returns_http_client(): void
    {
        $this->assertInstanceOf(HttpClientInterface::class, $this->bot->getHttpClient());
    }

    #[Test]
    public function it_returns_command_manager(): void
    {
        $this->assertInstanceOf(CommandManager::class, $this->bot->getCommandManager());
    }

    #[Test]
    public function it_can_send_message(): void
    {
        $expectedResponse = ['ok' => true, 'result' => ['message_id' => 123]];
        
        $this->mockHttpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://bot-api.zapps.me/bottest-token/sendMessage',
                ['chat_id' => 'test-chat', 'text' => 'Hello World'],
                [
                    'Content-Type' => 'application/json'
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->bot->sendMessage('test-chat', 'Hello World');

        $this->assertEquals($expectedResponse, $result);
    }

    #[Test]
    public function it_can_send_photo(): void
    {
        $expectedResponse = ['ok' => true, 'result' => ['message_id' => 124]];
        
        $this->mockHttpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://bot-api.zapps.me/bottest-token/sendPhoto',
                [
                    'chat_id' => 'test-chat',
                    'photo' => 'https://example.com/photo.jpg',
                    'caption' => 'Test photo'
                ],
                [
                    'Content-Type' => 'application/json'
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->bot->sendPhoto('test-chat', 'https://example.com/photo.jpg', 'Test photo');

        $this->assertEquals($expectedResponse, $result);
    }

    #[Test]
    public function it_can_send_sticker(): void
    {
        $expectedResponse = ['ok' => true, 'result' => ['message_id' => 125]];
        
        $this->mockHttpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://bot-api.zapps.me/bottest-token/sendSticker',
                [
                    'chat_id' => 'test-chat',
                    'sticker' => 'sticker_id_from_stickers.zaloapp.com'
                ],
                [
                    'Content-Type' => 'application/json'
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->bot->sendSticker('test-chat', 'sticker_id_from_stickers.zaloapp.com');

        $this->assertEquals($expectedResponse, $result);
    }

    #[Test]
    public function it_can_get_user_profile(): void
    {
        $expectedResponse = ['ok' => true, 'result' => ['id' => 'user123', 'name' => 'Test User']];
        
        $this->mockHttpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://bot-api.zapps.me/bottest-token/getUserProfile',
                ['user_id' => 'user123'],
                [
                    'Content-Type' => 'application/json'
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->bot->getUserProfile('user123');

        $this->assertEquals($expectedResponse, $result);
    }

    #[Test]
    public function it_can_set_webhook(): void
    {
        $expectedResponse = ['ok' => true, 'result' => true];
        
        $this->mockHttpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://bot-api.zapps.me/bottest-token/setWebhook',
                ['url' => 'https://example.com/webhook'],
                [
                    'Content-Type' => 'application/json'
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->bot->setWebhook('https://example.com/webhook');

        $this->assertEquals($expectedResponse, $result);
    }

    #[Test]
    public function it_can_delete_webhook(): void
    {
        $expectedResponse = ['ok' => true, 'result' => true];
        
        $this->mockHttpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://bot-api.zapps.me/bottest-token/deleteWebhook',
                [],
                [
                    'Content-Type' => 'application/json'
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->bot->deleteWebhook();

        $this->assertEquals($expectedResponse, $result);
    }

    #[Test]
    public function it_can_get_webhook_info(): void
    {
        $expectedResponse = ['ok' => true, 'result' => ['url' => 'https://example.com/webhook']];
        
        $this->mockHttpClient
            ->expects($this->once())
            ->method('post')
            ->with(
                'https://bot-api.zapps.me/bottest-token/getWebhookInfo',
                [],
                [
                    'Content-Type' => 'application/json'
                ]
            )
            ->willReturn($expectedResponse);

        $result = $this->bot->getWebhookInfo();

        $this->assertEquals($expectedResponse, $result);
    }
}
