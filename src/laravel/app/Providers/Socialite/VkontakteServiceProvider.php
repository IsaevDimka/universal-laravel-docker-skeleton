<?php

declare(strict_types=1);

namespace App\Providers\Socialite;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\InvalidStateException;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class VkontakteServiceProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'VKONTAKTE';

    /**
     * Last API version.
     */
    public const VERSION = '5.92';

    protected $fields = [
        'id',
        'email',
        'first_name',
        'last_name',
        'screen_name',
        'photo_200',
    ];

    protected $stateless = true;

    protected $scopes = ['email'];

    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException();
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject($this->getUserByToken($response));

        $this->credentialsResponseBody = $response;

        if ($user instanceof User) {
            $user->setAccessTokenResponseBody($this->credentialsResponseBody);
        }

        return $user->setToken($this->parseAccessToken($response))->setExpiresIn($this->parseExpiresIn($response));
    }

    /**
     * Set the user fields to request from Vkontakte.
     *
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public static function additionalConfigKeys()
    {
        return ['lang'];
    }

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://oauth.vk.com/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://oauth.vk.com/access_token';
    }

    protected function getUserByToken($token)
    {
        $from_token = [];
        if (is_array($token)) {
            $from_token['email'] = isset($token['email']) ? $token['email'] : null;

            $token = $token['access_token'];
        }

        $params = http_build_query([
            'access_token' => $token,
            'fields' => implode(',', $this->fields),
            'lang' => $this->getConfig('lang', 'en'),
            'v' => self::VERSION,
        ]);

        $response = $this->getHttpClient()->get('https://api.vk.com/method/users.get?' . $params);

        $contents = $response->getBody()->getContents();

        $response = json_decode($contents, true);

        if (! is_array($response) || ! isset($response['response'][0])) {
            throw new \RuntimeException(sprintf('Invalid JSON response from VK: %s', $contents));
        }

        return array_merge($from_token, $response['response'][0]);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id' => Arr::get($user, 'id'),
            'nickname' => Arr::get($user, 'screen_name'),
            'name' => trim(Arr::get($user, 'first_name') . ' ' . Arr::get($user, 'last_name')),
            'email' => Arr::get($user, 'email'),
            'avatar' => Arr::get($user, 'photo_200'),
        ]);
    }

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code',
        ]);
    }
}
