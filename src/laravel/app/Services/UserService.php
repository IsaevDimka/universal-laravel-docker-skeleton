<?php


namespace App\Services;

use App\Models\User;

class UserService
{
    /**
     * @return static
     */
    public static function instance(){
        return new static;
    }

    public function updateLastVisitAt($user_id) : void
    {
        $user = User::findOrFail($user_id);
        $user->last_visit_at = now();
        $user->timestamps = false;
        $user->save();
    }
}