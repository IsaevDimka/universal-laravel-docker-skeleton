<?php


namespace App\Channels;

use App\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Notifications\Notification;

/**
 * Class TelegramChannel
 * @package App\Channels
 * @TODO: upgrade from https://github.com/laravel-notification-channels/telegram
 * @TODO: refactoring by skeleton: https://github.com/laravel-notification-channels/skeleton/tree/master/src
 */
class TelegramChannel
{
    private const mongo_collection_processed = 'TelegramChannel_Processed';
    private const mongo_collection_failed = 'TelegramChannel_Failed';

    /** @var string */
    private $apiBaseUri = 'https://api.telegram.org';
    /** @var string */
    private $endpoint = 'sendMessage';
    /** @var array */
    private $headers = [
        'Content-type' => 'application/json'
    ];

    /** @var string $bot_key */
    private $bot_key;
    /** @var string $chat_id */
    private $chat_id;
    /** @var array $payload */
    private $payload = [];

    /**
     * Send the given notification.
     *
     * @param \Illuminate\Notifications\AnonymousNotifiable|\App\User $notifiable
     * @param Notification                                            $notification
     *
     * @return void
     * @throws Exception
     */
    public function send($notifiable, Notification $notification)
    {
        /** Check telegram public channel OR personal user notify */
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {
            $this->chat_id = $notifiable->routes['telegram'];
        } elseif ($notifiable instanceof User) {
            $this->chat_id = $notifiable->routeNotificationForTelegram();
        }

        /**
         * Set telegram bot key
         */
        $this->bot_key = config('notifications.telegram.botKey');

        $text = $notification->toTelegram($notifiable);

        $decoded_text = $this->entity_decoder($text);

        $this->payload = [
            'chat_id'                  => $this->chat_id,
            'text'                     => $decoded_text,
            'parse_mode'               => 'Markdown',
            'disable_web_page_preview' => true,
        ];

        if (!empty($links = $notification->getLinks())) {
            $this->payload = array_merge($this->payload, [
                'reply_markup' => [
                    'inline_keyboard' => [
                        $links
                    ],
                ],
            ]);
        }

        try {
            /** Check is empty chat_id */
            if (! $this->chat_id) throw new \Exception("Notify TelegramChannel telegram chat_id is empty");

            $client = new Client([
                'timeout'  => config('notifications.curl_timeout'),
                'headers'  => $this->headers,
            ]);

            $apiUri = sprintf('%s/bot%s/%s', $this->apiBaseUri, $this->bot_key, $this->endpoint);

            $response = $client->post($apiUri, [
                'json' => $this->payload,
            ]);

            $response_code = $response->getStatusCode();
            $response_json = $response->getBody()->getContents();

            if ($response_code !== 200) {
                throw new \Exception($response_json, $response_code);
            }

            $response_data = json_decode($response_json, true);

            logger()->channel('mongodb')->info('TelegramChannel', [
                'collection' => self::mongo_collection_processed,
                'chat_id'    => $this->chat_id,
                'payload'    => $this->payload,
                'response'   => [
                    'data' => $response_data,
                    'code' => $response_code,
                ]
            ]);
        } catch (RequestException $exception) {
            $this->exceptionHandle($exception);
        } catch (Exception $exception) {
            $this->exceptionHandle($exception);
        }
    }

    private function entity_decoder(string $text = '') : string
    {
        $decoded_text = \str_replace('_', "\_",  $text);
        $decoded_text = \str_replace('*', "\*",  $decoded_text);
        $decoded_text = \str_replace('[', "\[",  $decoded_text);
        $decoded_text = \str_replace(']', "\]",  $decoded_text);
        $decoded_text = \str_replace('`', "\`",  $decoded_text);
        return $decoded_text;
    }
    private function exceptionHandle(Exception $exception)
    {
        $error_payload = [
            'chat_id' => $this->chat_id,
            'payload' => $this->payload,
            'error'   => [
                'message' => (string)$exception->getMessage(),
                'line'    => (int)$exception->getLine(),
                'code'    => (int)$exception->getCode(),
            ],
        ];
        if ($exception instanceof \GuzzleHttp\Exception\RequestException) {
            $error_payload = array_merge($error_payload, [
                'exception' => 'RequestException',
                'request'   => [
                    'uri'     => (string)$exception->getRequest()->getUri() ?? null,
                    'method'  => $exception->getRequest()->getMethod() ?? null,
                    'headers' => (string)\json_encode($exception->getRequest()->getHeaders(), true) ?? null,
                    'body'    => (string)$exception->getRequest()->getBody() ?? null,
                ],
                'response'  => [
                    'code'    => $exception->getResponse()->getStatusCode() ?? null,
                    'headers' => (string)\json_encode($exception->getResponse()->getHeaders(), true) ?? null,
                    'body'    => (string)$exception->getResponse()->getBody() ?? null,
                ],
            ]);
        }

        logger()->channel('mongodb')->error('TelegramChannel', array_merge(['collection' => self::mongo_collection_failed], $error_payload));
        logger()->channel('telegram')->error('TelegramChannel', array_merge(['type' => 'clear'], $error_payload));

        throw new Exception((string)$exception->getMessage(), $exception->getCode());
    }
}
