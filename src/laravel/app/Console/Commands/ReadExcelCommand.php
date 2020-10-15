<?php

namespace App\Console\Commands;

use App\Models\Area;
use Illuminate\Console\Command;
use Rap2hpoutre\FastExcel\FastExcel;

class ReadExcelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:read';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $start_microtime = microtime(true);

        $path = database_path('example.xlsx');
        $collection = (new FastExcel())->import($path);
        foreach($collection as $key => $item)
        {
            $count_izbirkom = (int) $item['count'];
        }

        $this->comment("Duration: ".format_duration((microtime(true) - $start_microtime)));
    }
}
