<?php

declare(strict_types=1);

namespace ModelChangesHistory\Facades;

use Illuminate\Support\Facades\Facade;

/** @mixin \ModelChangesHistory\Services\HistoryStorageService */
class HistoryStorage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'historyStorage';
    }
}
