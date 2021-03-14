<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    public const API_URI = 'https://api.telegram.org/bot';

    public const CURL_TIMEOUT = 10000;

    public const mongodb_collection = 'telegram';

    protected $botKey;

    protected $webhook_url;

    public function __construct()
    {
        $this->botKey = env('TELEGRAM_BOT_TOKEN');
        $this->webhook_url = route('api.v1.telegram.webhook');
    }

    public function setWebhook(string $webhook_url = null)
    {
        $data = [
            'url' => $webhook_url ?? $this->webhook_url,
        ];
        return $this->sendRequest('/setWebhook', $data);
    }

    public function getWebhookInfo()
    {
        return $this->sendRequest('getWebhookInfo');
    }

    private function sendRequest(string $endpoint, array $data = [])
    {
        try {
            $response = Http::timeout(self::CURL_TIMEOUT)
                ->baseUrl(self::API_URI . $this->botKey)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->acceptJson()
                ->post($endpoint, $data)
                ->throw();

            $response_data = $response->json();

            throw_unless($response->ok(), RequestException::class, $response_data);

            logger()->channel('mongodb')->debug(__METHOD__, [
                'collection' => self::mongodb_collection,
                'api_uri' => self::API_URI,
                'botKey' => $this->botKey,
                'endpoint' => $endpoint,
                'data' => $data,
                'response' => [
                    'data' => $response_data,
                    'status' => $response->status(),
                ],
                'errors' => [],
            ]);
            return $response_data;
        } catch (RequestException $exception) {
            logger()->channel('mongodb')->error(__METHOD__, [
                'collection' => self::mongodb_collection,
                'api_uri' => self::API_URI,
                'botKey' => $this->botKey,
                'endpoint' => $endpoint,
                'data' => $data,
                'errors' => [
                    'message' => (string) $exception->getMessage(),
                    'code' => (int) $exception->getCode(),
                ],
            ]);
            return $exception->response
                ? $exception->response->json()
                : 'Error: ' . (string) $exception->getMessage() . ' | Code: ' . $exception->getCode();
        }
    }
}
