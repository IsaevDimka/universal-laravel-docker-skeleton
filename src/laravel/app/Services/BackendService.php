<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;
use Illuminate\Support\Facades\Redis;
use Laravel\Horizon\Contracts\MasterSupervisorRepository;

class BackendService
{
    const STATUS_FILE_NAME = 'status.json';

    const SERVICE_OPERATIONAL = 'Operational';
    const SERVICE_DOWN        = 'Down';
    const SERVICE_PAUSE       = 'Pause';
    const SERVICE_UPDATE      = 'Update';

    protected int $status = 200;
    protected string $message = '';
    protected array $errors = [];

    protected array $services = [
        'API'         => null,
        'Backend'     => null,
        'Frontend'    => null,
        'Session'     => null,
        'PostgresSQL' => null,
        'MongoDB'     => null,
        'ClickHouse'  => null,
        'Redis'       => null,
        'RabbitMQ'    => null,
        'Memcached'   => null,
        'RoadRunner'  => null,
        'Queue'       => null,
        'Mail'        => null,
        'Https'       => null,
    ];

    protected $environment;
    protected $locale;
    protected $version;
    protected $laravel_version;

    protected SessionStore $session_store;
    protected CacheManager $cache_manager;
    protected DatabaseManager $database_manager;
    protected MasterSupervisorRepository $horizon;

    public function __construct(
        SessionStore $session_store,
        CacheManager $cache_manager,
        DatabaseManager $database_manager,
        MasterSupervisorRepository $horizon
    ) {
        $this->environment      = app()->environment();
        $this->locale           = app()->getLocale();
        $this->laravel_version  = app()->version();
        $this->version          = (new \PragmaRX\Version\Package\Version())->format();
        $this->session_store    = $session_store;
        $this->cache_manager    = $cache_manager;
        $this->database_manager = $database_manager;
        $this->horizon          = $horizon;
    }

    /**
     * Uptime server
     */
    public function uptime()
    {
        $data   = shell_exec('uptime -p');
        $uptime = trim(str_replace([
            "up",
            "\n"
        ], '', $data));
        return $uptime;
    }

    /**
     * @return array
     */
    public function getStatus(): array
    {
        try{
            /**
             * Check memcached working
             */
            $this->cache_manager->store()
                                ->set($key = Str::random(), $value = Str::random(), 3);
            if($this->cache_manager->store()
                                   ->get($key) !== $value) {
                $this->services['Memcached'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Cache driver ' . $this->cache_manager->getDefaultDriver() . ' does not works as expected');
            }else{
                $this->services['Memcached'] = self::SERVICE_OPERATIONAL;
            }

            /**
             * Check sessions storage
             */
            $this->session_store->put($key = Str::random(), $value = Str::random());
            if($this->session_store->get($key) !== $value) {
                $this->services['Session'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Sessions {' . env('SESSION_DRIVER') . '} storage does not works as expected');
            }else{
                $this->services['Session'] = self::SERVICE_OPERATIONAL;
            }

            /**
             * Check default database PostgresSQL connection
             */
            try{
                $database                      = $this->database_manager->connection()
                                                                        ->unprepared('SELECT 1');
                $this->services['PostgresSQL'] = self::SERVICE_OPERATIONAL;
            } catch(\Illuminate\Database\QueryException $e){
                $this->services['PostgresSQL'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Database ' . $this->database_manager->connection()->getName() . ' database: ' . $this->database_manager->connection()->getDatabaseName() . ' does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check Redis
             */
            try{
                $redis_cli_ping = Redis::connection()
                                       ->command('PING');
                $this->services['Redis'] = self::SERVICE_OPERATIONAL;
            } catch(\Predis\Connection\ConnectionException $e){
                $this->services['Redis'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Redis does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check Yandex Clickhouse
             */
            try{
                $clickhouse                   = DB::connection('clickhouse')
                                                  ->select("SELECT 1");
                $this->services['ClickHouse'] = self::SERVICE_OPERATIONAL;
            } catch(\Tinderbox\Clickhouse\Exceptions\TransportException $e){
                $this->services['ClickHouse'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Yandex ClickHouse does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check MongoDB connection
             */
            try{
                $mongodb                   = $this->database_manager->connection('mongodb')
                                                                    ->getMongoDB()
                                                                    ->listCollections();
                $this->services['MongoDB'] = self::SERVICE_OPERATIONAL;
            } catch(\MongoDB\Driver\Exception\ConnectionTimeoutException | \MongoDB\Driver\Exception\AuthenticationException $e){
                $this->services['MongoDB'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Database MongoDB does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check RabbitMQ
             */
            try{
                $queue                      = app()['queue'];
                $rabbitmq                   = $queue->connection('rabbitmq');
                $this->services['RabbitMQ'] = self::SERVICE_OPERATIONAL;
            } catch(\PhpAmqpLib\Exception\AMQPIOException $e){
                $this->services['RabbitMQ'] = self::SERVICE_DOWN;
                array_push($this->errors, 'RabbitMQ does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check Laravel horizon queue
             */
            try{
                if(!$horizon = $this->horizon->all()) {
                    $this->services['Queue'] = self::SERVICE_DOWN;
                    array_push($this->errors, 'Horizon is inactive.');
                }else if($horizon_is_paused = collect($horizon)->contains(function($master) {
                    return $master->status === 'paused';
                })) {
                    $this->services['Queue'] = self::SERVICE_PAUSE;
                    array_push($this->errors, 'Horizon is paused.');
                }else{
                    $this->services['Queue'] = self::SERVICE_OPERATIONAL;
                }
            } catch(\Predis\Connection\ConnectionException $e){
                $this->services['Queue'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Horizon queue does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check API, Backend, Frontend
             */
            if(app()->isDownForMaintenance()) {
                $this->services['API'] = self::SERVICE_UPDATE;
                array_push($this->errors, 'API is currently under maintenance. Please stand by for a while as we are working on it.');
                $this->services['Backend'] = self::SERVICE_UPDATE;
                array_push($this->errors, 'Backend is currently under maintenance. Please stand by for a while as we are working on it.');
                $this->services['Frontend'] = self::SERVICE_UPDATE;
                array_push($this->errors, 'Frontend is currently under maintenance. Please stand by for a while as we are working on it.');
            }else{
                $this->services['API']      = self::SERVICE_OPERATIONAL;
                $this->services['Backend']  = self::SERVICE_OPERATIONAL;
                $this->services['Frontend'] = self::SERVICE_OPERATIONAL;
            }

            /**
             * Check RoadRunner
             */
//            try{
//                $roadrunner                   = Http::get(env('ROADRUNNER_HOST').':'.env('ROADRUNNER_HTTP_PORT'));
//                $this->services['RoadRunner'] = $roadrunner->ok() ? self::SERVICE_OPERATIONAL : self::SERVICE_DOWN;
//            } catch(\Illuminate\Http\Client\ConnectionException $e){
//                $this->services['RoadRunner'] = self::SERVICE_DOWN;
//                array_push($this->errors, 'RoadRunner service is experiencing some issues but our ninja developers are on it and should be back shortly!');
//            }
//
//            if(!empty($this->errors)) {
//                throw new RuntimeException('Some services unavailable', 500);
//            }

            $this->status  = 200;
            $this->message = 'Operational';
        } catch(Throwable $e){
            $this->message = $e->getMessage();
            $this->status  = 500;
        }

        return [
            'status'          => $this->status,
            'message'         => $this->message,
            'errors'          => $this->errors,
            'services'        => $this->services,
            'environment'     => $this->environment,
            'locale'          => $this->locale,
            'version'         => $this->version,
            'laravel_version' => $this->laravel_version,
            'uptime'          => $this->uptime(),
            'now'             => now()->toDateTimeString(),
        ];
    }

    /**
     * Update status in public/status.json
     */
    public function updateStatus(): void
    {
        $data = json_encode($this->getStatus(), JSON_PRETTY_PRINT);
        File::put(public_path(self::STATUS_FILE_NAME), $data);
    }
}
