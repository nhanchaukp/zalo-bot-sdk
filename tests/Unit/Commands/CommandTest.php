<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Tests\Unit\Commands;

use NhanChauKP\ZaloBotSdk\Commands\Command;
use NhanChauKP\ZaloBotSdk\Contracts\ZaloBotInterface;
use NhanChauKP\ZaloBotSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CommandTest extends TestCase
{
    private TestCommand $command;
    private ZaloBotInterface $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new TestCommand();
        $this->bot = $this->createMock(ZaloBotInterface::class);
    }

    #[Test]
    public function it_returns_correct_name(): void
    {
        $this->assertEquals('test', $this->command->getName());
    }

    #[Test]
    public function it_returns_correct_description(): void
    {
        $this->assertEquals('Test command', $this->command->getDescription());
    }

    #[Test]
    public function it_returns_correct_aliases(): void
    {
        $this->assertEquals(['t', 'testing'], $this->command->getAliases());
    }

    #[Test]
    public function it_matches_command_name(): void
    {
        $this->assertTrue($this->command->matches('test'));
        $this->assertFalse($this->command->matches('other'));
    }

    #[Test]
    public function it_matches_command_aliases(): void
    {
        $this->assertTrue($this->command->matches('t'));
        $this->assertTrue($this->command->matches('testing'));
        $this->assertFalse($this->command->matches('unknown'));
    }

    #[Test]
    public function it_extracts_chat_id_from_update(): void
    {
        $update = [
            'message' => [
                'chat' => ['id' => 'chat123'],
                'from' => ['id' => 'user456'],
                'text' => '/test hello world'
            ]
        ];

        $chatId = $this->command->getChatId($update);
        $this->assertEquals('chat123', $chatId);
    }

    #[Test]
    public function it_extracts_user_id_from_update(): void
    {
        $update = [
            'message' => [
                'chat' => ['id' => 'chat123'],
                'from' => ['id' => 'user456'],
                'text' => '/test hello world'
            ]
        ];

        $userId = $this->command->getUserId($update);
        $this->assertEquals('user456', $userId);
    }

    #[Test]
    public function it_extracts_message_text_from_update(): void
    {
        $update = [
            'message' => [
                'text' => '/test hello world'
            ]
        ];

        $text = $this->command->getMessageText($update);
        $this->assertEquals('/test hello world', $text);
    }

    #[Test]
    public function it_extracts_command_arguments(): void
    {
        $text = '/test hello world how are you';
        $args = $this->command->getCommandArguments($text);

        $this->assertEquals(['hello', 'world', 'how', 'are', 'you'], $args);
    }

    #[Test]
    public function it_can_reply_to_message(): void
    {
        $update = [
            'message' => [
                'chat' => ['id' => 'chat123'],
                'from' => ['id' => 'user456'],
                'text' => '/test'
            ]
        ];

        $this->bot
            ->expects($this->once())
            ->method('sendMessage')
            ->with('chat123', 'Test reply')
            ->willReturn(['ok' => true]);

        $result = $this->command->reply($update, $this->bot, 'Test reply');
        $this->assertEquals(['ok' => true], $result);
    }

    #[Test]
    public function it_throws_exception_when_chat_id_not_found(): void
    {
        $update = [
            'message' => [
                'from' => ['id' => 'user456'],
                'text' => '/test'
            ]
        ];

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Unable to determine chat ID from update.');

        $this->command->reply($update, $this->bot, 'Test reply');
    }
}

class TestCommand extends Command
{
    protected string $name = 'test';
    protected string $description = 'Test command';
    protected array $aliases = ['t', 'testing'];

    public function handle(array $update, ZaloBotInterface $bot): mixed
    {
        return $this->reply($update, $bot, 'Test command executed');
    }

    // Make protected methods public for testing
    public function getChatId(array $update): ?string
    {
        return parent::getChatId($update);
    }

    public function getUserId(array $update): ?string
    {
        return parent::getUserId($update);
    }

    public function getMessageText(array $update): ?string
    {
        return parent::getMessageText($update);
    }

    public function getCommandArguments(string $text): array
    {
        return parent::getCommandArguments($text);
    }

    public function reply(array $update, ZaloBotInterface $bot, string $message): array
    {
        return parent::reply($update, $bot, $message);
    }
}
