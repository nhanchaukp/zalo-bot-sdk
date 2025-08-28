<?php

declare(strict_types=1);

namespace NhanChauKP\ZaloBotSdk\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use NhanChauKP\ZaloBotSdk\Contracts\HttpClientInterface;
use NhanChauKP\ZaloBotSdk\Exceptions\ZaloBotException;

final class GuzzleHttpClient implements HttpClientInterface
{
    protected Client $client;

    public function __construct(array $config = [])
    {
        $this->client = new Client($config);
    }

    public function get(string $url, array $headers = []): array
    {
        try {
            $response = $this->client->get($url, [
                'headers' => $headers,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            throw new ZaloBotException('HTTP GET request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function post(string $url, array $data = [], array $headers = []): array
    {
        try {
            $response = $this->client->post($url, [
                'json' => $data,
                'headers' => $headers,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            throw new ZaloBotException('HTTP POST request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function put(string $url, array $data = [], array $headers = []): array
    {
        try {
            $response = $this->client->put($url, [
                'json' => $data,
                'headers' => $headers,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            throw new ZaloBotException('HTTP PUT request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function delete(string $url, array $headers = []): array
    {
        try {
            $response = $this->client->delete($url, [
                'headers' => $headers,
            ]);

            return json_decode($response->getBody()->getContents(), true) ?? [];
        } catch (GuzzleException $e) {
            throw new ZaloBotException('HTTP DELETE request failed: ' . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
