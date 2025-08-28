<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Tests\Unit\Commands;

use Illuminate\Support\Collection;
use NhanChauKP\ZaloBotSdk\Commands\Command;
use NhanChauKP\ZaloBotSdk\Commands\HelpCommand;
use NhanChauKP\ZaloBotSdk\Contracts\ZaloBotInterface;
use NhanChauKP\ZaloBotSdk\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class HelpCommandTest extends TestCase
{
    private HelpCommand $command;
    private ZaloBotInterface $bot;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new HelpCommand();
        $this->bot = $this->createMock(ZaloBotInterface::class);
    }

    #[Test]
    public function it_has_correct_properties(): void
    {
        $this->assertEquals('help', $this->command->getName());
        $this->assertEquals('Show available commands and their descriptions', $this->command->getDescription());
        $this->assertEquals(['start', 'commands'], $this->command->getAliases());
    }

    #[Test]
    public function it_handles_empty_commands_list(): void
    {
        $update = [
            'message' => [
                'chat' => ['id' => 'chat123'],
                'text' => '/help'
            ]
        ];

        $this->bot
            ->expects($this->once())
            ->method('getCommands')
            ->willReturn(new Collection());

        $this->bot
            ->expects($this->once())
            ->method('sendMessage')
            ->with('chat123', 'Không có lệnh nào được đăng ký.')
            ->willReturn(['ok' => true]);

        $result = $this->command->handle($update, $this->bot);
        $this->assertEquals(['ok' => true], $result);
    }

    #[Test]
    public function it_handles_commands_list_with_commands(): void
    {
        $update = [
            'message' => [
                'chat' => ['id' => 'chat123'],
                'text' => '/help'
            ]
        ];

        $mockCommand1 = $this->createMock(Command::class);
        $mockCommand1->method('getName')->willReturn('start');
        $mockCommand1->method('getDescription')->willReturn('Start the bot');
        $mockCommand1->method('getAliases')->willReturn(['begin']);

        $mockCommand2 = $this->createMock(Command::class);
        $mockCommand2->method('getName')->willReturn('info');
        $mockCommand2->method('getDescription')->willReturn('Get bot info');
        $mockCommand2->method('getAliases')->willReturn([]);

        $commands = new Collection([$mockCommand1, $mockCommand2]);

        $this->bot
            ->expects($this->once())
            ->method('getCommands')
            ->willReturn($commands);

        $expectedMessage = "📋 **Danh sách lệnh có sẵn:**\n\n";
        $expectedMessage .= "🔹 **/start** (aliases: /begin)\n   Start the bot\n\n";
        $expectedMessage .= "🔹 **/info**\n   Get bot info\n\n";
        $expectedMessage .= "💡 Gõ lệnh bằng cách thêm dấu `/` trước tên lệnh.";

        $this->bot
            ->expects($this->once())
            ->method('sendMessage')
            ->with('chat123', $expectedMessage)
            ->willReturn(['ok' => true]);

        $result = $this->command->handle($update, $this->bot);
        $this->assertEquals(['ok' => true], $result);
    }

    #[Test]
    public function it_handles_commands_without_aliases(): void
    {
        $update = [
            'message' => [
                'chat' => ['id' => 'chat123'],
                'text' => '/help'
            ]
        ];

        $mockCommand = $this->createMock(Command::class);
        $mockCommand->method('getName')->willReturn('test');
        $mockCommand->method('getDescription')->willReturn('Test command');
        $mockCommand->method('getAliases')->willReturn([]);

        $commands = new Collection([$mockCommand]);

        $this->bot
            ->expects($this->once())
            ->method('getCommands')
            ->willReturn($commands);

        $expectedMessage = "📋 **Danh sách lệnh có sẵn:**\n\n";
        $expectedMessage .= "🔹 **/test**\n   Test command\n\n";
        $expectedMessage .= "💡 Gõ lệnh bằng cách thêm dấu `/` trước tên lệnh.";

        $this->bot
            ->expects($this->once())
            ->method('sendMessage')
            ->with('chat123', $expectedMessage)
            ->willReturn(['ok' => true]);

        $result = $this->command->handle($update, $this->bot);
        $this->assertEquals(['ok' => true], $result);
    }
}
