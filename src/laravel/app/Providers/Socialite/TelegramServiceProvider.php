<?php

namespace App\Providers\Socialite;

use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class TelegramServiceProvider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'TELEGRAM';

    /**
     * @return array
     */
    public static function additionalConfigKeys()
    {
        return [
            'bot',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'        => $user['id'],
            'nickname'  => $user['username'],
            'name'      => $user['first_name'].' '.$user['last_name'],
            'avatar'    => $user['photo_url'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        $this->config = config('services.telegram');

        $validator = Validator::make($this->request->all(), [
            'id'        => 'required|numeric',
            'auth_date' => 'required|date_format:U|before:1 day',
            'hash'      => 'required|size:64',
        ]);

        throw_if($validator->fails(), InvalidArgumentException::class);

        $auth_data = $this->request->except('token', 'hash');
        $dataToHash = collect($auth_data)
            ->transform(function ($val, $key) {
                return "$key=$val";
            })
            ->sort()
            ->join("\n");

        $secret_key = hash('sha256', $this->config['client_secret'], true);
        $hash = hash_hmac('sha256', $dataToHash, $secret_key);
        throw_if(
            strcmp($hash, $this->request->hash) !== 0,
            InvalidArgumentException::class,
            'Data is NOT from Telegram'
        );
        throw_if(
            (time() - $auth_data['auth_date']) > 86400,
            InvalidArgumentException::class,
            'Data is outdated'
        );

        return $this->mapUserToObject($this->request->except(['auth_date', 'hash']));
    }
}
