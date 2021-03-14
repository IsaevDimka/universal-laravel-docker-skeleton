<?php

declare(strict_types=1);

namespace ModelChangesHistory\Console\Commands;

use Illuminate\Console\Command;
use ModelChangesHistory\Facades\HistoryStorage;

class ChangesHistoryClearCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'changes-history:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear changes history for all models.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        HistoryStorage::deleteHistoryChanges();
    }
}
