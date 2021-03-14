<?php

declare(strict_types=1);

namespace App\Listeners;

use Spatie\Backup\Events\BackupZipWasCreated;

class BackupZipWasCreatedNotification
{
    public function handle(BackupZipWasCreated $event)
    {
        logger()->channel('telegram')->debug('Backup zip was created successful', [
            'pathToZip' => $event->pathToZip,
        ]);
    }
}
