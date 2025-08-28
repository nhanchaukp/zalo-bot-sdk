<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Tests\Unit;

use Illuminate\Contracts\Foundation\Application;
use NhanChauKP\ZaloBotSdk\Services\ZaloBotManager;
use NhanChauKP\ZaloBotSdk\Tests\TestCase;
use NhanChauKP\ZaloBotSdk\ZaloBot;
use PHPUnit\Framework\Attributes\Test;

class ZaloBotManagerTest extends TestCase
{
    private ZaloBotManager $manager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = new ZaloBotManager($this->app);
    }

    #[Test]
    public function it_can_be_instantiated(): void
    {
        $this->assertInstanceOf(ZaloBotManager::class, $this->manager);
    }

    #[Test]
    public function it_returns_correct_default_bot_name(): void
    {
        $this->assertEquals('test', $this->manager->getDefaultBot());
    }

    #[Test]
    public function it_can_get_default_bot_instance(): void
    {
        $bot = $this->manager->bot();

        $this->assertInstanceOf(ZaloBot::class, $bot);
        $this->assertEquals('test-token', $bot->getToken());
    }

    #[Test]
    public function it_can_get_specific_bot_instance(): void
    {
        $bot = $this->manager->bot('test');

        $this->assertInstanceOf(ZaloBot::class, $bot);
        $this->assertEquals('test-token', $bot->getToken());
    }

    #[Test]
    public function it_returns_same_bot_instance_on_multiple_calls(): void
    {
        $bot1 = $this->manager->bot('test');
        $bot2 = $this->manager->bot('test');

        $this->assertSame($bot1, $bot2);
    }

    #[Test]
    public function it_can_check_if_bot_exists(): void
    {
        $this->assertTrue($this->manager->hasBot('test'));
        $this->assertFalse($this->manager->hasBot('nonexistent'));
    }

    #[Test]
    public function it_returns_bot_names(): void
    {
        $names = $this->manager->getBotNames();

        $this->assertIsArray($names);
        $this->assertContains('test', $names);
    }

    #[Test]
    public function it_can_call_methods_on_default_bot(): void
    {
        $token = $this->manager->getToken();

        $this->assertEquals('test-token', $token);
    }

    #[Test]
    public function it_throws_exception_for_nonexistent_bot(): void
    {
        $this->expectException(\NhanChauKP\ZaloBotSdk\Exceptions\ZaloBotException::class);
        $this->expectExceptionMessage("Bot configuration for 'nonexistent' not found.");

        $this->manager->bot('nonexistent');
    }

    #[Test]
    public function it_throws_exception_for_bot_without_token(): void
    {
        $this->app['config']->set('zalo-bot.bots.no_token', ['webhook_url' => 'https://example.com']);
        
        // Recreate manager to get new config
        $manager = new ZaloBotManager($this->app);

        $this->expectException(\NhanChauKP\ZaloBotSdk\Exceptions\ZaloBotException::class);
        $this->expectExceptionMessage("Bot token for 'no_token' is not configured.");

        $manager->bot('no_token');
    }
}
