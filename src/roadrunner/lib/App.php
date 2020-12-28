<?php
namespace Lib;

use Psr\Http\Message\ServerRequestInterface;
use Spiral\Goridge\StreamRelay;
use Spiral\Goridge\RelayInterface;
use Spiral\RoadRunner\PSR7Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Lib\Route as Route;
use Lib\Ip2Geo as Ip2Geo;
use Throwable;
use Psr\Http\Message\ResponseInterface;


class App
{
    const LOG_DIR = '/var/www/roadrunner/logs/';
    const BY_PASS_PRETTY = 'pretty';
    protected array $data = [];
    public float $rr_worker_start;
    protected int $maxExecs;

    function __construct() {
        $this->maxExecs = (int) \getenv('MAX_EXECS') ?? 1000;
    }

    /**
     * @return static
     */
    public static function instance()
    {
        return new static;
    }

    public function start()
    {
        $psr7_client = $this->createPsr7Client($this->createStreamRelay());

        $relay = new \Spiral\Goridge\SocketRelay("127.0.0.1", 6001);
        $rpc = new \Spiral\Goridge\RPC($relay);
        $metrics = new \Spiral\RoadRunner\Metrics($rpc);

        $count_execs = 0;
        while($psrRequest = $psr7_client->acceptRequest()){
            if ($count_execs++ > $this->maxExecs) {
                $psr7_client->getWorker()->stop();
                continue;
            }

            try{
                $this->rr_worker_start = \microtime(true);

                $psrResponse = new \Zend\Diactoros\Response();

                Route::add('/', function() use ($psrRequest) {
                    $this->data = ['path' => $psrRequest->getUri()->getPath()];
                });

                Route::add('/ls/accept', function() use ($psrRequest) {
                    $ls = new \Lib\LeadStorage();
                    $ls->accept($psrRequest);
                    $this->data = $ls->getPayload();
                });

                Route::add('/ping', function() use ($psrRequest) {
                    $this->data = ['path' => $psrRequest->getUri()->getPath()];
                });

                Route::add('/error', function() {
                    throw new \Error('Error', 500);
                });

                Route::add('/foo/([0-9]*)/bar', fn($var1) => $this->data = ['foo' => $var1]);

                Route::add('/post', function() use ($psrRequest) {
                    $this->data = ['request_body' => \json_decode($psrRequest->getBody()->getContents(), 1)];
                }, 'POST');

                Route::add('/status', fn() => $this->data = [
                    'uptime'        => $this->uptime(),
                    'phpversion'    => phpversion(),
                ]);

                Route::add('/jit', fn() => $this->data = ['jit' => opcache_get_status()['jit']]);

                /**
                 * wrk -t12 -c400 -d10s http://localhost:8082/api/v1/wrk
                 */
                Route::add('/wrk', function() use ($count_execs, $psrRequest) {
                    $duration = $this->format_duration((microtime(true) - $this->rr_worker_start));
                    $this->setDebugLog('wrk.txt', $count_execs . " | " . $duration);
                    $this->data = ['path' => $psrRequest->getUri()->getPath()];
                    //                    $log = new Logger('roadrunner');
                    //                    $log->pushHandler(new StreamHandler(self::LOG_DIR.'/wrk-'.date('Y-m-d').'.log'));
                    //                    $log->debug('wrk', ['duration' => $duration]);
                });

                /**
                 * ab -n 1000 -c 10 -l http://localhost:8082/api/v1/ab
                 */
                Route::add('/ab', function() use ($count_execs, $psrRequest) {
                    $duration = $this->format_duration((microtime(true) - $this->rr_worker_start));
                    $this->setDebugLog('ab.txt', $count_execs . " | " . $duration);
                    $this->data = ['path' => $psrRequest->getUri()->getPath()];
                    //                    $log = new Logger('roadrunner');
                    //                    $log->pushHandler(new StreamHandler(self::LOG_DIR.'/ab-'.date('Y-m-d').'.log'));
                    //                    $log->debug('Apache Bench', ['duration' => $duration]);
                });

                Route::add('/geoip', function() use ($psrRequest) {
//                    $ip_address = '118.69.35.47';
                    $ip_address = $psrRequest->getServerParams()['REMOTE_ADDR'] ?? null;
                    $geoip = new Ip2Geo();
                    $geoip->setIp($ip_address);
                    $this->data = [
                        'ip_address'       => $ip_address,
                        'geoip'            => $geoip->getResult(),
                        'country_iso_code' => $geoip->getCountryIsoCode(),
                        'user_agent'       => $_SERVER['HTTP_USER_AGENT'],
                    ];
                });

                Route::add('/workers', fn() => $this->data = $rpc->call('http.Workers', true));

                Route::add('/debug', function () use ($psrRequest) {
                    $this->data = [
                        'cookie' => $psrRequest->getCookieParams(),
                        'CID' => $psrRequest->getCookieParams()['CID'] ?? null,
                        'pid' => (string) getmypid(),
                    ];
                });

                Route::add('/tds', function($weights = [
                    'node_1' => 50,
                    'node_2' => 30,
                    'node_3' => 20,
                ]) {
                    $result = TDS::getKeyByWeights($weights);
                    $this->data = [
                        'getKeyByWeights' => $result,
                        'weights'         => $weights,
                    ];
                    $this->setDebugLog('tds.txt', $result['node'] . " | " . \json_encode($result));
                });

                Route::run();

                $duration = (float) (microtime(true) - $this->rr_worker_start);
                $this->data = array_merge($this->data, [
                    'status'    => 200,
                    'app_debug' => $this->isDebugModeEnabled(),
                    'maxExecs'  => $this->maxExecs,
                    'version'   => \getenv('VERSION'),
                    'duration'  => $this->format_duration($duration),
                ]);

                $psrResponse = $this->handleRequest($psrRequest, $psrResponse, Api::ok(null, $this->data));
//                $psrResponse = $psrResponse
//                    ->withHeader('X-test', 'something')
//                    ->withAddedHeader('X-Show-Something', 'something');
//                $metrics->add('app_metric_duration', $duration);
                $psr7_client->respond($psrResponse->withStatus(Api::HTTP_OK));
                $metrics->add('app_metric_counter_ok', 1);
            } catch(Throwable $e){
                $metrics->add('app_metric_counter_error', 1);
                if ($e instanceof NotFoundHttpException) {
                    $psrResponse = $this->handleRequest($psrRequest, $psrResponse, Api::notFound($e->getMessage()));
                    $psr7_client->respond($psrResponse->withStatus(Api::HTTP_NOT_FOUND));
                }else if ($e instanceof MethodNotAllowedHttpException) {
                    $psrResponse = $this->handleRequest($psrRequest, $psrResponse, Api::response(Api::HTTP_METHOD_NOT_ALLOWED, $e->getMessage()));
                    $psr7_client->respond($psrResponse->withStatus(Api::HTTP_METHOD_NOT_ALLOWED));
                }else{
                    $this->fireEvent($e);
                    $json = \json_encode(Api::error($this->exceptionToString($e, $this->isDebugModeEnabled())), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    $psr7_client->getWorker()->error($json);
                }
            }
        }
    }

    protected function handleRequest(ServerRequestInterface $psrRequest, ResponseInterface $psrResponse, array $data = []) : ResponseInterface
    {
        $queryParams = $psrRequest->getQueryParams();

        if($queryParams[self::BY_PASS_PRETTY]) {
            $json = \json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }else{
            $json = \json_encode($data);
        }

        $psrResponse->getBody()->write($json);

        if (! isset($psrRequest->getCookieParams()['CID'])) {
            return $psrResponse->withAddedHeader(
                "Set-Cookie",
                (new Cookie('CID', TDS::generate_hash(10)))->createHeader()
            );
        }

        return $psrResponse;
    }

    private function setDebugLog(string $filename = 'log.txt', string $line = '') : void
    {
        $file = self::LOG_DIR.$filename;
        $log  = fopen($file, 'a');
        fwrite($log, $line.PHP_EOL);
        fclose($log);
    }

    /**
     * @param RelayInterface $stream_relay
     *
     * @return PSR7Client
     */
    protected function createPsr7Client(RelayInterface $stream_relay): PSR7Client
    {
        return new PSR7Client(new \Spiral\RoadRunner\Worker($stream_relay));
    }

    /**
     * @param resource|mixed $in  Must be readable
     * @param resource|mixed $out Must be writable
     *
     * @return RelayInterface
     */
    protected function createStreamRelay($in = \STDIN, $out = \STDOUT): RelayInterface {
        return new StreamRelay($in, $out);
    }

    /**
     * @param $seconds
     *
     * @return string
     */
    public function format_duration($seconds) : string
    {
        if($seconds < 0.001) {
            return round($seconds * 1000000) . 'μs';
        }elseif($seconds < 1){
            return round($seconds * 1000, 2) . 'ms';
        }

        return round($seconds, 2) . 's';
    }

    /**
     * @param Throwable $e
     * @param bool      $is_debug
     *
     * @return string
     */
    protected function exceptionToString(Throwable $e, bool $is_debug): string
    {
        return $is_debug ? (string) $e->getMessage() : 'Internal server error';
    }

    protected function isDebugModeEnabled(): bool
    {
        return getenv('DEBUG_MODE') ?? false;
    }

    protected function fireEvent(\Throwable $e) : void
    {
        $log = new Logger('roadrunner');
        $log->pushHandler(new StreamHandler(self::LOG_DIR.'/roadrunner-'.date('Y-m-d').'.log'));
        $log->error($e);
    }

    protected function uptime() : string
    {
        return trim(str_replace(["up", "\n"], '', shell_exec('uptime -p')));
    }
}