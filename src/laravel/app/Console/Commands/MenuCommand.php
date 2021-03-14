<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
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
        $option = $this->menu('Pizza menu')
            ->addOption('mozzarella', 'Mozzarella')
            ->addOption('chicken_parm', 'Chicken Parm')
            ->addOption('sausage', 'Sausage')
            ->addQuestion('Make your own', 'Describe your pizza...')
            ->addOption('burger', 'Prefer burgers')
            ->setWidth(80)
            ->open();

        $this->info("You have chosen the text option: ${option}");
    }
}
