<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * after a record has been retrieved.
     */
    public function retrieved(User $user)
    {
    }

    /**
     * before a record has been created.
     */
    public function creating(User $user)
    {
    }

    /**
     * after a record has been created.
     */
    public function created(User $user)
    {
    }

    /**
     * before a record is updated.
     */
    public function updating(User $user)
    {
    }

    /**
     * after a record has been updated.
     */
    public function updated(User $user)
    {
    }

    /**
     * before a record is saved (either created or updated).
     */
    public function saving(User $user)
    {
    }

    /**
     * after a record has been saved (either created or updated).
     */
    public function saved(User $user)
    {
    }

    /**
     * before a record is deleted or soft-deleted.
     */
    public function deleting(User $user)
    {
    }

    /**
     * after a record has been deleted or soft-deleted.
     */
    public function deleted(User $user)
    {
    }

    /**
     * before a soft-deleted record is going to be restored.
     */
    public function restoring(User $user)
    {
    }

    /**
     * after a soft-deleted record has been restored.
     */
    public function restored(User $user)
    {
    }

    /**
     * after a force Deleted record.
     */
    public function forceDeleted(User $user)
    {
    }
}
