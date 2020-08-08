<?php

namespace App\Models;

use App\Casts\Json;
use App\Casts\Locale;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $phone
 * @property string|null $telegram_chat_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $last_visit_at
 * @property string $password
 * @property bool $is_active
 * @property string $locale
 * @property array|null $options
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLastVisitAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereUsername($value)
 * @mixin Authenticatable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User notActive()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OAuthProvider[] $oauthProviders
 * @property-read int|null $oauth_providers_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\User whereTelegramChatId($value)
 */
class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference, JWTSubject
{
    use Notifiable;
    use HasRoles;

    const FORMAT_DATETIME = 'Y-m-d H:i:s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'phone',
        'telegram_chat_id',
        'email_verified_at',
        'last_visit_at',
        'password',
        'is_active',
        'locale',
        'options',
    ];

    protected $dates = [
        'email_verified_at',
        'last_visit_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_visit_at'     => 'datetime',
        'is_active'         => 'boolean',
        'options'           => Json::class,
        'locale'            => Locale::class,
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function(User $model) {
            $model->password = \Illuminate\Support\Facades\Hash::make($model->password);
        });
        static::updating(function(User $model) {
            $model->username = strtolower($model->username);
            $model->email    = strtolower($model->email);
        });
    }

    /**
     * Lowercase username
     * @param $value
     */
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    /**
     * Lowercase email
     * @param $value
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Scope a query to only include active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Scope a query to only include not active users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotActive($query)
    {
        return $query->where('is_active', 0);
    }

    /**
     * Определяем, был ли пользователь approved
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->is_active === null;
    }

    /**
     * Определяем, что пользователь не approved
     * @return bool
     */
    public function isNotActive() : bool
    {
        return ! $this->isActive();
    }

    public function routeNotificationFor($driver)
    {
        if (method_exists($this, $method = 'routeNotificationFor'.Str::studly($driver))) {
            return $this->{$method}();
        }

        switch ($driver) {
            case 'database':
                return $this->notifications();
            case 'mail':
                return $this->email;
            case 'sms':
                return $this->phone;
            case 'telegram':
                return $this->telegram_chat_id;
        }
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string|null
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    /**
     * Route notifications for the sms channel.
     *
     * @return string|null
     */
    public function routeNotificationForSms()
    {
        return $this->phone;
    }

    /**
     * Route notifications for the telegram channel.
     *
     * @return string|null
     */
    public function routeNotificationForTelegram()
    {
        return $this->telegram_chat_id;
    }

    /**
     * @return string|null
     */
    public function preferredLocale()
    {
        return $this->locale;
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'users.'.$this->id;
    }

    /**
     * Check valid email domain mx records
     *
     * @return bool
     */
    public function hasValidEmail() : bool
    {
        [$username, $domain] = explode('@', $this->email);

        return checkdnsrr($domain, 'MX');
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(\App\Models\OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCreatedAtAttribute($value)
    {
        return BaseModel::formatingCarbonAttribute($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return BaseModel::formatingCarbonAttribute($value);
    }

    public function getLastVisitAtAttribute($value)
    {
        return BaseModel::formatingCarbonAttribute($value);
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return BaseModel::formatingCarbonAttribute($value);
    }
}
