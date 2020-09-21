<?php


namespace App\Traits;

trait checkAuthAdmin
{

    public function checkAuthAdmin()
    {
        if (! app()->environment(['local', 'develop'])) {
            if (auth()->guest()) {
                return api()->forbidden();
            }

            /** @var \App\Models\User $user */
            $user = auth()->user();
            $roles = ['root', 'admin', 'manager'];
            if (! $user->hasRole($roles)) {
                return api()->forbidden();
            }
        }

    }
}