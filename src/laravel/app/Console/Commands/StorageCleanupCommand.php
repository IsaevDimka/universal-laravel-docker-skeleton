<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class StorageCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:cleanup
                            {--disk=local : local, remote-backup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup storage disks: local, remote-backup';

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
        $duration_start = microtime(true);

        $disk = $this->option('disk');

        switch ($disk)
        {
            # local
            default:
                $this->cleanupLocal();
                break;

            case 'local':
                $this->cleanupLocal();
                break;

            case 'remote-backup':
                $this->cleanupRemoteBackup();
                break;
        }

        $this->info('Duration: '.format_duration(microtime(true) - $duration_start));
    }

    private function cleanupLocal()
    {
        $exclude_files = [
            '.gitignore',
            'laravel-'.now()->format('Y-m-d').'.log',
        ];

        $storage_directories = [
            'app/public/exports/',
            'logs',
        ];

        # clean local storage
        foreach ($storage_directories as $directory)
        {
            $files = File::allFiles(storage_path($directory));
            foreach ($files as $file)
            {
                if(! str_is($exclude_files, $file->getBasename()))
                {
                    File::delete($file->getPathname());
                }
            }
        }

        $this->line('Cleanup storage disk local successful');
    }

    /** @todo need improve */
    private function cleanupRemoteBackup()
    {
        $storage_directories = [
            'mongodb/',
            'postgres/'
        ];
        $exclude_files = [
        ];

        # clean sftp-backup storage
        foreach ($storage_directories as $directory)
        {
            $files = Storage::disk('sftp-backup-server')->allFiles($directory);
            //            dd($files);
            foreach ($files as $file)
            {
                $filename = str_replace($storage_directories, '', $file);
                $this->line($filename);
            }

        }
        $this->line('Cleanup storage disk sftp-backup successful');
    }
}
