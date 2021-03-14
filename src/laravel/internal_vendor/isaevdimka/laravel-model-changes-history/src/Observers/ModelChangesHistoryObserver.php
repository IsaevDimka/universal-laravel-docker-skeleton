<?php

declare(strict_types=1);

namespace ModelChangesHistory\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use ModelChangesHistory\Facades\ChangesHistory;
use ModelChangesHistory\Facades\HistoryStorage;
use ModelChangesHistory\Models\Change;

class ModelChangesHistoryObserver
{
    /**
     * @var array
     */
    protected $ignoredActions;

    public function __construct()
    {
        $this->ignoredActions = config('model_changes_history.ignored_actions', []);
    }

    public function created(Model $model)
    {
        if (! in_array(Change::TYPE_CREATED, $this->ignoredActions)) {
            HistoryStorage::recordChange(ChangesHistory::createChange(Change::TYPE_CREATED, $model, Auth::user()));
        }
    }

    public function updated(Model $model)
    {
        if (! in_array(Change::TYPE_UPDATED, $this->ignoredActions)) {
            HistoryStorage::recordChange(ChangesHistory::createChange(Change::TYPE_UPDATED, $model, Auth::user()));
        }
    }

    public function deleted(Model $model)
    {
        if (! in_array(Change::TYPE_DELETED, $this->ignoredActions)) {
            HistoryStorage::recordChange(ChangesHistory::createChange(Change::TYPE_DELETED, $model, Auth::user()));
        }
    }

    public function restored(Model $model)
    {
        if (! in_array(Change::TYPE_RESTORED, $this->ignoredActions)) {
            HistoryStorage::recordChange(ChangesHistory::createChange(Change::TYPE_RESTORED, $model, Auth::user()));
        }
    }

    public function forceDeleted(Model $model)
    {
        if (! in_array(Change::TYPE_FORCE_DELETED, $this->ignoredActions)) {
            HistoryStorage::recordChange(ChangesHistory::createChange(Change::TYPE_FORCE_DELETED, $model, Auth::user()));
        }
    }
}
