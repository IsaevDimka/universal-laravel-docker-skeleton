<?php

declare(strict_types=1);

namespace App\Providers\Socialite;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class ZaloServiceProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'ZALO';

    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->get($this->getTokenUrl(), [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'query' => $this->getTokenFields($code),
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://oauth.zaloapp.com/v3/auth', $state);
    }

    protected function getCodeFields($state = null)
    {
        $fields = [
            'app_id' => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
            'state' => $state,
        ];

        return array_merge($fields, $this->parameters);
    }

    protected function getTokenUrl()
    {
        return 'https://oauth.zaloapp.com/v3/access_token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://graph.zalo.me/v2.0/me?access_token=' . $token . '&fields=id,birthday,name,gender,picture');

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => null,
            'name' => $user['name'],
            'avatar' => preg_replace('/^http:/i', 'https:', $user['picture']['data']['url']),
        ]);
    }

    protected function getTokenFields($code)
    {
        return [
            'app_id' => $this->clientId,
            'app_secret' => $this->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->redirectUrl,
        ];
    }
}
