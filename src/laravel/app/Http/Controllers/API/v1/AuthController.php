<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\API\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * API Create user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {
        $validator = Validator::make($request->post(), [
            'first_name'       => 'required|string',
            'last_name'        => 'required|string',
            'username'         => 'required|string|unique:users',
            'email'            => 'required|string|email|unique:users',
            'phone'            => 'required|string',
            'password'         => 'required',
            'confirm_password' => 'required|same:password',
        ]);
        if($validator->fails()){
            return api()->validation('Error', $validator->errors()->toArray());
        }

        $payload = $validator->validated();

        /** @var User $user */
        $user = User::create($payload);
        if($user){
            $token = $user->createToken('token')->accessToken;
            return api()->ok('Registration successfull.', $user->toArray(), compact('token'));
        }else{
            return api()->validation('Sorry! Registration is not successfull.');
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->only('email', 'password'), [
            'email'     => 'required|string|email',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return api()->validation('Error', $validator->errors()->toArray());
        }

        $credentials = $validator->validated();

        if (!Auth::attempt($credentials)) {
            return api()->forbidden('Unauthorized.');
        }

        /** @var User $user */
        $user = $request->user();
        if ($user->hasRole('root')) {
            $success['token'] = $user->createToken('root token', ['root'])->accessToken;
        } else {
            $success['token'] = $user->createToken('token')->accessToken;
        }

        return api()->ok('Success', $success);
    }

    /**
     * API logout user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $isUser = $request->user()->token()->revoke();
        if($isUser){
            return api()->ok('Successfully logged out.');
        }
        else{
            return api()->validation('Something went wrong.');
        }
    }

    public function getUser(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        if($user){
            return api()->ok('Success', $user->toArray());
        } else{
            return api()->notFound('User not found');
        }
    }
}
