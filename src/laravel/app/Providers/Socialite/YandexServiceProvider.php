<?php

declare(strict_types=1);

namespace App\Providers\Socialite;

use Illuminate\Support\Arr;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class YandexServiceProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'YANDEX';

    public function getAccessTokenResponse($code)
    {
        $query = array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
        unset($query['redirect_uri']);

        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Accept' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => $query,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://oauth.yandex.ru/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://oauth.yandex.ru/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('https://login.yandex.ru/info?format=json', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['login'],
            'name' => Arr::get($user, 'real_name'),
            'email' => Arr::get($user, 'default_email'),
            'avatar' => 'https://avatars.yandex.net/get-yapic/' . Arr::get($user, 'default_avatar_id') . '/islands-200',
        ]);
    }

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
