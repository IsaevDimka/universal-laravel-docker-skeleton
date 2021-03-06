<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Settings;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    /**
     * Update the user's profile information.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        /**
         * TODO: Make request
         */
        $this->validate($request, [
            'username' => 'required|string|max:256|unique:users,username,' . $user->id,
            'first_name' => 'nullable||string|max:256',
            'last_name' => 'nullable||string|max:256',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:256',
            'telegram_chat_id' => 'nullable|string|max:256',
        ]);

        return tap($user)->update($request->only('email', 'first_name', 'last_name', 'email', 'phone', 'telegram_chat_id'));
    }
}
