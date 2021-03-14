<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class UserService
{
    public static function instance(): self
    {
        return new static();
    }

    public function updateLastVisitAt($user_id): void
    {
        $user = User::findOrFail($user_id);
        $user->last_visit_at = now();
        $user->timestamps = false;
        $user->save();
    }
}
