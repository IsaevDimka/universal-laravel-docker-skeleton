<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{

    /**
     * after a record has been retrieved.
     * @param  \App\Models\User  $user
     */
    public function retrieved(User $user)
    {

    }

    /**
     * before a record has been created.
     * @param  \App\Models\User  $user
     */
    public function creating(User $user)
    {

    }

    /**
     * after a record has been created.
     * @param  \App\Models\User  $user
     */
    public function created(User $user)
    {

    }

    /**
     * before a record is updated.
     * @param  \App\Models\User  $user
     */
    public function updating(User $user)
    {

    }

    /**
     * after a record has been updated.
     * @param  \App\Models\User  $user
     */
    public function updated(User $user)
    {

    }

    /**
     * before a record is saved (either created or updated).
     * @param  \App\Models\User  $user
     */
    public function saving(User $user)
    {

    }

    /**
     * after a record has been saved (either created or updated).
     * @param  \App\Models\User  $user
     */
    public function saved(User $user)
    {

    }

    /**
     * before a record is deleted or soft-deleted.
     * @param  \App\Models\User  $user
     */
    public function deleting(User $user)
    {

    }

    /**
     * after a record has been deleted or soft-deleted.
     * @param  \App\Models\User  $user
     */
    public function deleted(User $user)
    {

    }

    /**
     * before a soft-deleted record is going to be restored.
     * @param  \App\Models\User  $user
     */
    public function restoring(User $user)
    {

    }

    /**
     * after a soft-deleted record has been restored.
     * @param  \App\Models\User  $user
     */
    public function restored(User $user)
    {

    }

    /**
     * after a force Deleted record.
     * @param  \App\Models\User  $user
     */
    public function forceDeleted(User $user)
    {

    }
}
