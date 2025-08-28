<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Commands;

use NhanChauKP\ZaloBotSdk\Contracts\ZaloBotInterface;

final class HelpCommand extends Command
{
    protected string $name = 'help';
    protected string $description = 'Show available commands and their descriptions';
    protected array $aliases = ['start', 'commands'];

    public function handle(array $update, ZaloBotInterface $bot): mixed
    {
        $commands = $bot->getCommands();
        
        if ($commands->isEmpty()) {
            return $this->reply($update, $bot, 'Không có lệnh nào được đăng ký.');
        }

        $message = "📋 **Danh sách lệnh có sẵn:**\n\n";
        
        foreach ($commands as $command) {
            if ($command instanceof Command) {
                $name = $command->getName();
                $description = $command->getDescription();
                $aliases = $command->getAliases();
                
                $message .= "🔹 **/{$name}**";
                
                if (!empty($aliases)) {
                    $message .= " (aliases: " . implode(', ', array_map(fn($alias) => "/{$alias}", $aliases)) . ")";
                }
                
                $message .= "\n   {$description}\n\n";
            }
        }

        $message .= "💡 Gõ lệnh bằng cách thêm dấu `/` trước tên lệnh.";

        return $this->reply($update, $bot, $message);
    }
}
