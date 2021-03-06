<?php

declare(strict_types=1);

namespace ModelChangesHistory\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ModelChangesHistory\Exceptions\StorageNotFoundException;
use ModelChangesHistory\Interfaces\HistoryStorageInterface;
use ModelChangesHistory\Models\Change;
use ModelChangesHistory\Stores\HistoryStorageRegistry;

class HistoryStorageService
{
    /**
     * @var bool
     */
    protected $recordHistoryChanges;

    /**
     * @var HistoryStorageInterface
     */
    protected $historyStorage;

    /**
     * HistoryStorageService constructor.
     *
     * @throws StorageNotFoundException
     */
    public function __construct()
    {
        $recordChangesOnlyForDebug = config('model_changes_history.use_only_for_debug', true)
            ? config('app.debug')
            : true;

        $this->recordHistoryChanges = config('model_changes_history.record_changes_history', true)
            ? $recordChangesOnlyForDebug
            : false;

        $this->historyStorage = HistoryStorageRegistry::create()
            ->get(config('model_changes_history.storage', HistoryStorageRegistry::STORAGE_DATABASE));
    }

    public function recordChange(Change $change): void
    {
        if ($this->recordHistoryChanges) {
            $this->historyStorage->recordChange($change);
        }
    }

    public function getHistoryChanges(?Model $model = null): Collection
    {
        return $this->historyStorage->getHistoryChanges($model);
    }

    public function getLatestChange(?Model $model = null): ?Change
    {
        return $this->historyStorage->getLatestChange($model);
    }

    public function deleteHistoryChanges(?Model $model = null): void
    {
        $this->historyStorage->deleteHistoryChanges($model);
    }
}
