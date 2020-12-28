<?php

namespace Logger;

//use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class TelegramHandler extends AbstractProcessingHandler
{
    protected $botKey;
    protected $chatId;
    protected $proxy;

    protected const DEFAULT_TYPE = 'default';

    public function __construct(array $config, int $level = Logger::DEBUG, bool $bubble = true)
    {
        $this->botKey = $config['botKey'];
        $this->chatId = $config['chatId'];
//        $this->proxy = $config['proxy'];

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {
        if (!empty($record)) {
            try {
                $type = $record['context']['type'] ?? self::DEFAULT_TYPE;
                unset($record['context']['type']);

                $message = '*' . $record['level_name'] . '* on ' . config('app.name') . ' ' . (new \PragmaRX\Version\Package\Version())->format() . PHP_EOL;
                $message .= 'ENV: `' . config('app.env') . '`' . PHP_EOL;
                $message .= 'Url: `' . env('APP_URL', 'null') . '`' . PHP_EOL;
                $message .= 'Locale: `' . app()->getLocale() . '`' . PHP_EOL;

                switch ($type):
                    default:
                        $message .= 'Remote Address: ' . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N/A') . PHP_EOL;
                        $message .= 'User Agent :' . (isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'N/A') . PHP_EOL;
                        $message .= 'User: ' . (auth()->id() ?? 'None') . PHP_EOL;
                        $message .= 'Message: ' . PHP_EOL;
                        $message .= '```json' . PHP_EOL . $record['message'] . PHP_EOL . '```';
                        if ($record['context']) {
                            $message .= 'Context: ' . PHP_EOL;
                            $message .= '```json' . PHP_EOL . \json_encode($record['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL . '```';
                        }
                        break;
                    case 'clear':
                        $message .= 'Message: ' . PHP_EOL;
                        $message .= '```json' . PHP_EOL . $record['message'] . PHP_EOL . '```';
                        if ($record['context']) {
                            $message .= 'Context: ' . PHP_EOL;
                            $message .= '```json' . PHP_EOL . \json_encode($record['context'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL . '```';
                        }
                        break;
                    case 'message':
                        $message = $record['message'];
                        break;
                endswitch;

                $messageArray = [
                    'chat_id'                  => $this->chatId,
                    'text'                     => $message,
                    'parse_mode'               => 'Markdown',
                    'disable_web_page_preview' => true,
                ];

                $apiUri = 'https://api.telegram.org/bot' . $this->botKey . '/sendMessage';

//                $client = new Client([
//                    'timeout'  => 4.0,
//                    'base_uri' => $apiUri,
//                ]);
//
//                if ($this->proxy) {
//                    $client->post('sendMessage', [
//                        'query' => $messageArray,
//                        'proxy' => $this->proxy,
//                    ]);
//
//                } else {
//                    $client->post('sendMessage', [
//                        'query' => $messageArray,
//                    ]);
//                }

                Http::timeout(5)->post($apiUri, $messageArray);

            } catch (\Throwable $e) {
                logger()->error('Send logs to Telegram chat via Telegram bot failed: ' . (string)$e->getMessage());
            }
        }
    }
}
