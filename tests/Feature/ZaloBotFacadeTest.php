<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Tests\Feature;

use NhanChauKP\ZaloBotSdk\Facades\ZaloBot;
use NhanChauKP\ZaloBotSdk\Services\ZaloBotManager;
use NhanChauKP\ZaloBotSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ZaloBotFacadeTest extends TestCase
{
    #[Test]
    public function it_resolves_to_zalo_bot_manager(): void
    {
        $manager = ZaloBot::getFacadeRoot();

        $this->assertInstanceOf(ZaloBotManager::class, $manager);
    }

    #[Test]
    public function it_can_get_default_bot_via_facade(): void
    {
        $defaultBot = ZaloBot::getDefaultBot();

        $this->assertEquals('test', $defaultBot);
    }

    #[Test]
    public function it_can_get_bot_names_via_facade(): void
    {
        $botNames = ZaloBot::getBotNames();

        $this->assertIsArray($botNames);
        $this->assertContains('test', $botNames);
    }

    #[Test]
    public function it_can_check_if_bot_exists_via_facade(): void
    {
        $this->assertTrue(ZaloBot::hasBot('test'));
        $this->assertFalse(ZaloBot::hasBot('nonexistent'));
    }

    #[Test]
    public function it_can_get_bot_instance_via_facade(): void
    {
        $bot = ZaloBot::bot('test');

        $this->assertInstanceOf(\NhanChauKP\ZaloBotSdk\ZaloBot::class, $bot);
        $this->assertEquals('test-token', $bot->getToken());
    }

    #[Test]
    public function it_can_call_bot_methods_directly_via_facade(): void
    {
        $token = ZaloBot::getToken();

        $this->assertEquals('test-token', $token);
    }

    #[Test]
    public function it_can_get_commands_via_facade(): void
    {
        $commands = ZaloBot::getCommands();

        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $commands);
    }
}
