<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Cache\CacheManager;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use PragmaRX\Version\Package\Version;
use RuntimeException;
use Spatie\Regex\Regex;
use Spatie\SslCertificate\SslCertificate;
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
        'Socket.io'   => null,
    ];

    protected $environment;
    protected $locale;
    protected $version;
    protected $latest_release;
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
        $version = (new Version());

        $this->environment      = app()->environment();
        $this->locale           = app()->getLocale();
        $this->laravel_version  = app()->version();
        $this->version          = $version->format();
        $this->latest_release   = Carbon::create($version->format('timestamp-datetime'))->toDateTimeString();
        $this->session_store    = $session_store;
        $this->cache_manager    = $cache_manager;
        $this->database_manager = $database_manager;
        $this->horizon          = $horizon;
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
            $random_cache_key = Str::random();
            $random_cache_value = Str::random();
            $this->cache_manager->store()->set($random_cache_key, $random_cache_value, 60);
            if($this->cache_manager->store()->get($random_cache_key) !== $random_cache_value) {
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
                $database = $this->database_manager->connection('pgsql')->unprepared('SELECT 1');
                $this->services['PostgresSQL'] = self::SERVICE_OPERATIONAL;
            } catch(\Illuminate\Database\QueryException $e){
                $this->services['PostgresSQL'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Database ' . $this->database_manager->connection()
                                                                               ->getName() . ' database: ' . $this->database_manager->connection()
                                                                                                                                    ->getDatabaseName() . ' does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check Redis
             */
            try{
                $redis_cli_ping          = Redis::connection()->command('PING');
                $this->services['Redis'] = self::SERVICE_OPERATIONAL;
            } catch(\Predis\Connection\ConnectionException $e){
                $this->services['Redis'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Redis does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check Yandex Clickhouse
             */
            try{
                $clickhouse                   = $this->database_manager->connection('clickhouse')->select("SELECT 1");
                $this->services['ClickHouse'] = self::SERVICE_OPERATIONAL;
            } catch(\Tinderbox\Clickhouse\Exceptions\TransportException $e){
                $this->services['ClickHouse'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Yandex ClickHouse does not works as expected. Error: ' . (string) $e->getMessage());
            }

            /**
             * Check MongoDB connection
             */
            try{
                $mongodb                   = $this->database_manager->connection('mongodb')->getMongoDB()->listCollections();
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
            try{
                $roadrunner                   = Http::get(env('ROADRUNNER_HOST').':'.env('ROADRUNNER_HEALTH_CHECK_PORT'));
                $this->services['RoadRunner'] = $roadrunner->ok() ? self::SERVICE_OPERATIONAL : self::SERVICE_DOWN;
            } catch(\Illuminate\Http\Client\ConnectionException $e){
                $this->services['RoadRunner'] = self::SERVICE_DOWN;
                array_push($this->errors, 'RoadRunner service is experiencing some issues but our ninja developers are on it and should be back shortly!');
            }

            /**
             * Check Socket.io | laravel echo server
             */
            try{
                $laravel_echo_server = Http::get(env('APP_URL').'/socket.io/?transport=polling');
                $this->services['Socket.io'] = $laravel_echo_server->ok() ? self::SERVICE_OPERATIONAL : self::SERVICE_DOWN;
            } catch(\Illuminate\Http\Client\ConnectionException $e){
                $this->services['Socket.io'] = self::SERVICE_DOWN;
                array_push($this->errors, 'Socket.io does not works as expected');
            }

            if(!empty($this->errors)) {
                throw new RuntimeException('Some services unavailable', 500);
            }

            /**
             * Ssl
             */
            if (! app()->environment('local')) {
                $check_ssl_url             = env('APP_URL', '');
                $check_ssl_expiration_days = config('monitor.check_certificates.expiration_days');
                try{
                    $certificate      = SslCertificate::createForHostName($check_ssl_url);
                    $check_ssl_result = [
                        'url'                       => $check_ssl_url,
                        'domain'                    => $certificate->getDomain(),
                        'isValidUntil'              => $certificate->isValidUntil(now()->addDays($check_ssl_expiration_days)),
                        'AdditionalDomains'         => $certificate->getAdditionalDomains(),
                        'Issuer'                    => $certificate->getIssuer(),
                        'isValid'                   => $certificate->isValid(),
                        'validFromDate'             => $certificate->validFromDate()->format('Y-m-d H:i:s'),
                        'expirationDate'            => $certificate->expirationDate()->format('Y-m-d H:i:s'),
                        'expirationDate_diffInDays' => $certificate->expirationDate()->diffInDays(),
                        'SignatureAlgorithm'        => $certificate->getSignatureAlgorithm(),
                        'isExpired'                 => $certificate->isExpired(),
                    ];
                    if($check_ssl_result['isValidUntil'] === false) {
                        $this->services['Https'] = self::SERVICE_DOWN;
                        array_push($this->errors, " Checking certificate of {$check_ssl_result['domain']}: is valid until {$check_ssl_expiration_days} days");
                    }
                    if($check_ssl_result['isExpired'] === true) {
                        $this->services['Https'] = self::SERVICE_DOWN;
                        array_push($this->errors, " Checking certificate of {$check_ssl_result['domain']}: Certificate is is expired");
                    }
                    $this->services['Https'] = self::SERVICE_OPERATIONAL;
                } catch(\Spatie\SslCertificate\Exceptions\CouldNotDownloadCertificate $e){
                    $this->services['Https'] = self::SERVICE_DOWN;
                    array_push($this->errors, "Checking certificate of {$check_ssl_url}: " . (string) $e->getMessage());
                }
            }

            $this->status  = 200;
            $this->message = 'Operational';
        } catch(Throwable $e){
            $this->message = $e->getMessage();
            $this->status  = 500;
        }

        return [
            'status'            => $this->status,
            'message'           => $this->message,
            'errors'            => $this->errors,
            'services'          => $this->services,
            'environment'       => $this->environment,
            'locale'            => $this->locale,
            'version'           => $this->version,
            'latest_release'    => $this->latest_release,
            'laravel_version'   => $this->laravel_version,
            'now'               => now()->toDateTimeString(),
            'uptime_server'     => $this->uptimeServer(),
            'uptime_docker'     => $this->uptimeDocker(),
            'average_cpu_usage' => $this->getCPUUsagePercentage().'%',
            'disk_space_enough' => $this->getDiskUsage().'%',
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

    public function getCPUUsagePercentage() : float
    {
        $cpu = shell_exec("grep 'cpu ' /proc/stat | awk '{usage=($2+$4)*100/($2+$4+$5)} END {print usage}'");

        return round((float) $cpu, 2);
    }

    public function getDiskUsagePercentage(string $commandOutput): int
    {
        return (int) Regex::match('/(\d?\d)%/', $commandOutput)->group(1);
    }

    public function getDiskUsage() : float
    {
        $totalSpace = disk_total_space(base_path());
        $freeSpace  = disk_free_space(base_path());
        $usedSpace  = $totalSpace - $freeSpace;

        return round(($usedSpace / $totalSpace) * 100);
    }

    public function uptimeServer() : string
    {
        $shellOutput   = shell_exec('uptime -p');
        return trim(str_replace(["up", "\n"], '', $shellOutput));
    }

    public function uptimeDocker() : string
    {
        $shellOutput = shell_exec('stat /proc/1/cmdline');
        $result = Str::after($shellOutput, "Change: ");
        $result = Str::before($result, ".");
        $result = trim($result);

        $carbon = Carbon::createFromFormat('Y-m-d H:i:s', $result);
        $now = Carbon::now();
        $diff = $now->diff($carbon);

        $uptime = "";
        if(($years = $diff->y) > 0) $uptime .= "$years ".trans_choice('carbon.years', $years).", ";
        if(($months = $diff->m) > 0) $uptime .= "$months ".trans_choice('carbon.months', $months).", ";
        if(($days = $diff->d) > 0) $uptime .= "$days ".trans_choice('carbon.days', $days).", ";
        if(($hours = $diff->h) > 0) $uptime .= "$hours ".trans_choice('carbon.hours', $hours).", ";
        if(($minutes = $diff->i) > 0) $uptime .= "$minutes ".trans_choice('carbon.minutes', $minutes);
        if(($seconds = $diff->s) > 0 && $minutes < 1) $uptime .= "$seconds ".trans_choice('carbon.seconds', $seconds);

        return $uptime;
    }
}
