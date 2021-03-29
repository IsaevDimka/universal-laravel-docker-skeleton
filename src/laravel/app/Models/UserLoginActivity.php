<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Json;

/**
 * App\Models\UserLoginActivity
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip_address
 * @property array $geoip
 * @property string $user_agent
 * @property array $parse_user_agent
 * @property bool $is_current
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity forCurrentUser()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereGeoip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereParseUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserLoginActivity whereUserId($value)
 * @mixin \Eloquent
 * @mixin IdeHelperUserLoginActivity
 */
class UserLoginActivity extends AbstractModel
{
    protected $table = 'user_login_activities';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'ip_address',
        'geoip',
        'user_agent',
        'parse_user_agent',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'geoip' => Json::class,
        'parse_user_agent' => Json::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForCurrentUser()
    {
        return $this->where('user_id', '=', auth()->id());
    }
}
