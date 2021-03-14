<?php

declare(strict_types=1);

namespace App\Providers\Socialite;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class GitLabServiceProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'GITLAB';

    protected $scopeSeparator = ' ';

    public static function additionalConfigKeys()
    {
        return ['instance_uri'];
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase($this->getInstanceUri() . 'oauth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return $this->getInstanceUri() . 'oauth/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get($this->getInstanceUri() . 'api/v4/user', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => $user['avatar_url'],
        ]);
    }

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }

    protected function getInstanceUri()
    {
        return $this->getConfig('instance_uri', 'https://gitlab.com/');
    }
}
