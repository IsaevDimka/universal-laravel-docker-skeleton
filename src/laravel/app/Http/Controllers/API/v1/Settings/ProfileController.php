<?php

namespace App\Http\Controllers\API\v1\Settings;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;

class ProfileController extends ApiController
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        /**
         * TODO: Make request
         */
        $this->validate($request, [
            'username'         => 'required|string|max:256|unique:users,username',
            'first_name'       => 'nullable||string|max:256',
            'last_name'        => 'nullable||string|max:256',
            'email'            => 'required|email|unique:users,email,' . $user->id,
            'phone'            => 'nullable|string|max:256',
            'telegram_chat_id' => 'nullable|string|max:256',
        ]);

        return tap($user)->update($request->only('username', 'email', 'first_name', 'last_name', 'phone', 'telegram_chat_id'));
    }
}
