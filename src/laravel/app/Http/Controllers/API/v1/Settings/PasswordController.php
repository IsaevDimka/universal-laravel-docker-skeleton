<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\v1\Settings;

use App\Http\Controllers\API\ApiController;
use Illuminate\Http\Request;

class PasswordController extends ApiController
{
    /**
     * Update the user's password.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! \Hash::check($request->get('current_password'), $user->password)) {
            return api()->validation('Current password invalid');
        }

        $user->update([
            'password' => $request->password,
        ]);

        return api()->ok('Password updated');
    }
}
