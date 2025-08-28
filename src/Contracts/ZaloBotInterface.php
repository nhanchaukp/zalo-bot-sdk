<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Contracts;

use Illuminate\Support\Collection;

interface ZaloBotInterface
{
    public function getToken(): string;
    public function getBaseUrl(): string;
    public function getHttpClient(): HttpClientInterface;
    public function sendMessage(string $chatId, string $text): array;
    public function sendPhoto(string $chatId, string $photo, ?string $caption = null): array;
    public function sendDocument(string $chatId, string $document, ?string $caption = null): array;
    public function getUserProfile(string $userId): array;
    public function setWebhook(string $url): array;
    public function deleteWebhook(): array;
    public function getWebhookInfo(): array;
    public function addCommand(string $command): self;
    public function addCommands(array $commands): self;
    public function removeCommand(string $command): self;
    public function getCommands(): Collection;
    public function processUpdate(array $update): mixed;
}
