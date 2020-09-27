<?php

namespace App\Http\Controllers\API\v1\Auth;

use App\Exceptions\EmailTakenException;
use App\Http\Controllers\API\ApiController;
use App\Models\OAuthProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends ApiController
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function redirectToProvider($provider)
    {
        try{
            $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        }catch(\Throwable $e)
        {
            return api()->validation($e->getMessage());
        }
        return api()->ok(null, compact('url'));
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $driver
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleProviderCallback($provider)
    {
        try{
            $user = Socialite::driver($provider)->stateless()->user();
        }catch(\Throwable $e)
        {
            return api()->validation($e->getMessage());
        }

        $user = $this->findOrCreateUser($provider, $user);

        $this->guard()->setToken($token = $this->guard()->login($user));
        $token      = (string) $this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return api()->ok(null, [
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiration - time(),
            'locale'     => $user->locale,
        ]);
    }

    /**
     * @param string                            $provider
     * @param \Laravel\Socialite\Contracts\User $sUser
     *
     * @return \App\Models\User|false
     */
    protected function findOrCreateUser(
        $provider,
        $user
    ) {
        $oauthProvider = OAuthProvider::where('provider', $provider)->where('provider_user_id', $user->getId())->first();

        if($oauthProvider) {
            $oauthProvider->update([
                'access_token'  => $user->token,
                'refresh_token' => $user->refreshToken,
            ]);

            return $oauthProvider->user;
        }

        if(User::where('email', $user->getEmail())->exists()) {
            throw new EmailTakenException;
        }

        return $this->createUser($provider, $user);
    }

    /**
     * @param string                            $provider
     * @param \Laravel\Socialite\Contracts\User $sUser
     *
     * @return \App\Models\User
     */
    protected function createUser(
        $provider,
        $sUser
    ) {
        $user = User::create([
            'name'              => $sUser->getName(),
            'username'          => $sUser->getNickname(),
            'email'             => $sUser->getEmail(),
            'email_verified_at' => now(),
            'avatar'            => $sUser->getAvatar(),
            'locale'            => config('app.fallback_locale'),
            'is_active'         => true,
            'options'           => null,
        ]);

        $user->assignRole(\App\Models\Role::ROLE_CLIENT);

        $user->oauthProviders()->create([
            'provider'         => $provider,
            'provider_user_id' => $sUser->getId(),
            'access_token'     => $sUser->token,
            'refresh_token'    => $sUser->refreshToken,
        ]);
        return $user;
    }
}
