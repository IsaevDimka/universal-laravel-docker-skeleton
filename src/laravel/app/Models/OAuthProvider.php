<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\OAuthProvider
 *
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $provider_user_id
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereProviderUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\OAuthProvider whereUserId($value)
 * @mixin \Eloquent
 * @property int $userId
 * @property string $providerUserId
 * @property string|null $accessToken
 * @property string|null $refreshToken
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @mixin IdeHelperOAuthProvider
 */
class OAuthProvider extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'oauth_providers';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'access_token', 'refresh_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
