<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MongoDBCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mongodb {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'method: list, delete, prune';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $duration_start = microtime(true);
        $type = $this->argument('type');
        switch ($type){
            default:
                break;

            case 'list': $this->list(); break;
            case 'delete': $this->delete(); break;
            case 'prune': $this->prune(); break;
        }


        $this->line(round(microtime(true) - $duration_start, 2).' sec.');
    }

    private function list()
    {
        $mongodb = \DB::connection('mongodb')->getMongoDB()->listCollections();
        foreach ($mongodb as $collection) {
            $name = $collection->getName();
            $this->comment($name);
        }
    }

    private function delete($date = null)
    {
        if(! $date){
            $date = $this->ask('Date (Y-m-D)?');
        }

        $dbmongo = \DB::connection('mongodb')->getMongoDB()->listCollections();
        foreach ($dbmongo as $collection) {
            $name = $collection->getName();
            $check = strripos($name, $date);
            if($check === false){
                \DB::connection('mongodb')->collection($name)->truncate();
                $this->error('Deleted collection: '.$name);
            }else{
                $this->info('Save collection: '. $name);
            }
        }
        logger()->channel('telegram')->info('Deleted all collection MongoDB without '.$date, ['type' => 'clear']);
    }

    private function prune()
    {
        $api_last_backup_time = 'http://165.22.55.105:8080/last-backup-time.json';
        /**
         * @example json format:
         * {"date": "2020-01-03", "timestamp": "1577998807"}
         */

        $client = new \GuzzleHttp\Client([
            'timeout' => 2.0,
        ]);

        try {
            $response = $client->get($api_last_backup_time);
            $response_code = $response->getStatusCode();
            if($response_code !== 200)
            {
                throw new \RuntimeException('Backup server error: '.$api_last_backup_time, $response_code);
            }
            $last_backup_time = \json_decode($response->getBody()->getContents());
            if($last_backup_time->date === now()->format('Y-m-d')){
                $this->delete($last_backup_time->date);
            }else{
                throw new \RuntimeException('Last backup time error: '.$last_backup_time->date, $response_code);
            }
        }catch (\Throwable $exception){
            logger()->channel('mongodb')->error('Error', [
                'collection'    => 'Artisan_failed',
                'command'       => 'mongodb prune',
                'errorMessage'  => $exception->getMessage()
            ]);
            logger()->channel('telegram')->error($exception->getMessage(), ['type' => 'clear']);
            $this->error($exception->getMessage());
        }
    }
}
