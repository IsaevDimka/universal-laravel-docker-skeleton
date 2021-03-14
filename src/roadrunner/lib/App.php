<?php

declare(strict_types=1);

namespace Lib;

use Carbon\Carbon;
use Lib\Exceptions\MethodNotAllowedHttpException;
use Lib\Exceptions\NotFoundHttpException;
use Lib\Ip2Geo as Ip2Geo;
use Lib\Route as Route;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\Goridge\RelayInterface;
use Spiral\Goridge\StreamRelay;
use Spiral\RoadRunner\PSR7Client;
use Throwable;

class App
{
    public const LOG_DIR = '/var/www/roadrunner/logs/';

    public const BY_PASS_PRETTY = 'pretty';

    public float $rr_worker_start;

    protected static $instance;

    protected array $data = [];

    protected int $maxExecs;

    public function __construct()
    {
        $this->maxExecs = (int) \getenv('MAX_EXECS') ?? 1000;
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function run()
    {
        $psr7_client = $this->createPsr7Client($this->createStreamRelay());

        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $relay = new \Spiral\Goridge\SocketRelay('127.0.0.1', 6001);
        $rpc = new \Spiral\Goridge\RPC($relay);
        $metrics = new \Spiral\RoadRunner\Metrics($rpc);

        $count_execs = 0;
        while ($psrRequest = $psr7_client->acceptRequest()) {
            if ($count_execs++ > $this->maxExecs) {
                $psr7_client->getWorker()->stop();
                continue;
            }

            $psrResponse = $psr17Factory->createResponse();

            try {
                $this->rr_worker_start = \microtime(true);

                Route::add('/ls/accept', function () use ($psrRequest) {
                    $ls = new \Lib\LeadStorage();
                    $ls->accept($psrRequest);
                    $this->data = $ls->getPayload();
                });

                Route::add('/ping', function () use ($psrRequest) {
                    $this->data = [
                        'path' => $psrRequest->getUri()->getPath(),
                    ];
                });

                Route::add('/error', function () {
                    throw new \Error('Error', 500);
                });

                Route::add('/foo/([0-9]*)/bar', fn ($var1) => $this->data = [
                    'foo' => $var1,
                ]);

                Route::add('/post', function () use ($psrRequest) {
                    $this->data = [
                        'request_body' => \json_decode($psrRequest->getBody()->getContents(), true),
                    ];
                }, 'POST');

                Route::add('/health', fn () => $this->data = [
                    'uptime_server' => $this->uptimeServer(),
                    'uptime_docker' => $this->uptimeDocker(),
                    'phpversion' => PHP_VERSION,
                    'timezone' => date_default_timezone_get(),
                ]);

                Route::add('/jit', fn () => $this->data = [
                    'jit' => opcache_get_status()['jit'],
                ]);

                /**
                 * wrk -t12 -c400 -d10s http://localhost:8082/api/v1/wrk
                 */
                Route::add('/wrk', function () use ($count_execs, $psrRequest) {
                    $duration = $this->formatDuration((microtime(true) - $this->rr_worker_start));
                    $this->setDebugLog('wrk.txt', $count_execs . ' | ' . $duration);
                    $this->data = [
                        'path' => $psrRequest->getUri()->getPath(),
                    ];
                    //                    $log = new Logger('roadrunner');
                    //                    $log->pushHandler(new StreamHandler(self::LOG_DIR.'/wrk-'.date('Y-m-d').'.log'));
                    //                    $log->debug('wrk', ['duration' => $duration]);
                });

                /**
                 * ab -n 1000 -c 10 -l http://localhost:8082/api/v1/ab
                 */
                Route::add('/ab', function () use ($count_execs, $psrRequest) {
                    $duration = $this->formatDuration((microtime(true) - $this->rr_worker_start));
                    $this->setDebugLog('ab.txt', $count_execs . ' | ' . $duration);
                    $this->data = [
                        'path' => $psrRequest->getUri()->getPath(),
                    ];
                    //                    $log = new Logger('roadrunner');
                    //                    $log->pushHandler(new StreamHandler(self::LOG_DIR.'/ab-'.date('Y-m-d').'.log'));
                    //                    $log->debug('Apache Bench', ['duration' => $duration]);
                });

                Route::add('/geoip', function () use ($psrRequest) {
//                    $ip_address = '118.69.35.47';
                    $ip_address = $psrRequest->getServerParams()['REMOTE_ADDR'] ?? null;
                    $geoip = new Ip2Geo();
                    $geoip->setIp($ip_address);
                    $this->data = [
                        'ip_address' => $ip_address,
                        'geoip' => $geoip->getResult(),
                        'country_iso_code' => $geoip->getCountryIsoCode(),
                        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                    ];
                });

                Route::add('/workers', fn () => $this->data = $rpc->call('http.Workers', true));

                Route::add('/debug', function () use ($psrRequest) {
                    $this->data = [
                        'cookie' => $psrRequest->getCookieParams(),
                        'CID' => $psrRequest->getCookieParams()['CID'] ?? null,
                        'pid' => (string) getmypid(),
                    ];
                });

                Route::add('/tds', function ($weights = [
                    'node_1' => 50,
                    'node_2' => 30,
                    'node_3' => 20,
                ]) {
                    $result = TDS::getKeyByWeights($weights);
                    $this->data = [
                        'getKeyByWeights' => $result,
                        'weights' => $weights,
                    ];
                    $this->setDebugLog('tds.txt', $result['node'] . ' | ' . \json_encode($result));
                });

                Route::add('/', function () use ($psrRequest) {
                    $this->data = [
                        'path' => $psrRequest->getUri()->getPath(),
                    ];
                });

                Route::run();

                $duration = (float) (microtime(true) - $this->rr_worker_start);
                $this->data = array_merge($this->data, [
                    'app_debug' => $this->isDebugModeEnabled(),
                    'maxExecs' => $this->maxExecs,
                    'version' => \getenv('VERSION'),
                    'duration' => $this->formatDuration($duration),
                ]);

                $psrResponse = $this->handleRequest($psrRequest, $psrResponse, Api::ok(null, $this->data));
//                $psrResponse = $psrResponse
//                    ->withHeader('X-test', 'something')
//                    ->withAddedHeader('X-Show-Something', 'something');
//                $metrics->add('app_metric_duration', $duration);
                $psr7_client->respond($psrResponse->withStatus(Api::HTTP_OK));
                $metrics->add('app_metric_counter_ok', 1);
            } catch (Throwable $e) {
                $metrics->add('app_metric_counter_error', 1);
                if ($e instanceof NotFoundHttpException) {
                    $psrResponse = $this->handleRequest($psrRequest, $psrResponse, Api::notFound($e->getMessage()));
                    $psr7_client->respond($psrResponse->withStatus(Api::HTTP_NOT_FOUND));
                } elseif ($e instanceof MethodNotAllowedHttpException) {
                    $psrResponse = $this->handleRequest($psrRequest, $psrResponse, Api::response(Api::HTTP_METHOD_NOT_ALLOWED, $e->getMessage()));
                    $psr7_client->respond($psrResponse->withStatus(Api::HTTP_METHOD_NOT_ALLOWED));
                } else {
                    $this->fireEvent($e);
                    $json = \json_encode(Api::error($this->exceptionToString($e, $this->isDebugModeEnabled())), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $psr7_client->getWorker()->error($json);
                }
            }
        }
    }

    /**
     * @param $seconds
     */
    public function formatDuration($seconds): string
    {
        if ($seconds < 0.001) {
            return round($seconds * 1000000) . 'μs';
        } elseif ($seconds < 1) {
            return round($seconds * 1000, 2) . 'ms';
        }

        return round($seconds, 2) . 's';
    }

    protected function handleRequest(ServerRequestInterface $psrRequest, ResponseInterface $psrResponse, array $data = []): ResponseInterface
    {
        $queryParams = $psrRequest->getQueryParams();

        if ($queryParams[self::BY_PASS_PRETTY]) {
            $json = \json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            $json = \json_encode($data);
        }

        $psrResponse->getBody()->write($json);

        if (! isset($psrRequest->getCookieParams()['CID'])) {
            return $psrResponse->withAddedHeader(
                'Set-Cookie',
                (new Cookie('CID', TDS::generate_hash(10)))->createHeader()
            );
        }

        return $psrResponse;
    }

    protected function createPsr7Client(RelayInterface $stream_relay): PSR7Client
    {
        return new PSR7Client(new \Spiral\RoadRunner\Worker($stream_relay));
    }

    /**
     * @param resource|mixed $in  Must be readable
     * @param resource|mixed $out Must be writable
     */
    protected function createStreamRelay($in = \STDIN, $out = \STDOUT): RelayInterface
    {
        return new StreamRelay($in, $out);
    }

    protected function exceptionToString(Throwable $e, bool $is_debug): string
    {
        return $is_debug ? (string) $e->getMessage() : 'Internal server error';
    }

    protected function isDebugModeEnabled(): bool
    {
        return (bool) ! ! getenv('DEBUG_MODE');
    }

    protected function fireEvent(\Throwable $e): void
    {
        $log = new Logger('roadrunner');
        $log->pushHandler(new StreamHandler(self::LOG_DIR . '/roadrunner-' . date('Y-m-d') . '.log'));
        $log->error($e);
    }

    protected function uptimeServer(): string
    {
        return trim(str_replace(['up', "\n"], '', shell_exec('uptime -p')));
    }

    protected function uptimeDocker(): string
    {
        $shellOutput = shell_exec('stat /proc/1/cmdline');
        preg_match('/Change: (.*).*?\\n Birth/s', $shellOutput, $matches);
        $result = strstr(trim($matches[1]), '.', true);
        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $result);
        $now = Carbon::now();
        $diff = $now->diff($carbon);

        $uptime = '';
        if (($years = $diff->y) > 0) {
            $uptime .= "${years} years, ";
        }
        if (($months = $diff->m) > 0) {
            $uptime .= "${months} months, ";
        }
        if (($days = $diff->d) > 0) {
            $uptime .= "${days} days, ";
        }
        if (($hours = $diff->h) > 0) {
            $uptime .= "${hours} weeks, ";
        }
        if (($minutes = $diff->i) > 0) {
            $uptime .= "${minutes} minutes";
        }
        if (($seconds = $diff->s) > 0 && $minutes < 1) {
            $uptime .= "${seconds} seconds";
        }

        return $uptime;
    }

    private function setDebugLog(string $filename = 'log.txt', string $line = ''): void
    {
        $file = self::LOG_DIR . $filename;
        $log = fopen($file, 'a');
        fwrite($log, $line . PHP_EOL);
        fclose($log);
    }
}
