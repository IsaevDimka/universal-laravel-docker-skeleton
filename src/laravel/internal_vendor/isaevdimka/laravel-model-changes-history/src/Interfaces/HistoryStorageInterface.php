<?php

declare(strict_types=1);

namespace ModelChangesHistory\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ModelChangesHistory\Models\Change;

interface HistoryStorageInterface
{
    /**
     * This function will record Change model to history storage
     */
    public function recordChange(Change $change): void;

    /**
     * This function will return all changes history using storage.
     * If the model is set - return all changes history for it.
     */
    public function getHistoryChanges(?Model $model = null): Collection;

    /**
     * This function will return the latest change using storage.
     * If the model is set - return the latest change for it.
     */
    public function getLatestChange(?Model $model = null): ?Change;

    /**
     * This function will delete all changes history using storage.
     * If the model is set - clear all changes history for it.
     */
    public function deleteHistoryChanges(?Model $model = null): void;
}
