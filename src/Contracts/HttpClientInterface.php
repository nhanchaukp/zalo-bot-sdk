<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Contracts;

interface HttpClientInterface
{
    /**
     * Send a GET request
     */
    public function get(string $url, array $headers = []): array;

    /**
     * Send a POST request
     */
    public function post(string $url, array $data = [], array $headers = []): array;

    /**
     * Send a PUT request
     */
    public function put(string $url, array $data = [], array $headers = []): array;

    /**
     * Send a DELETE request
     */
    public function delete(string $url, array $headers = []): array;
}
