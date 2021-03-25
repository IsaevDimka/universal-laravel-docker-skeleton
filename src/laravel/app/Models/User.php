<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Json;
use App\Casts\Locale;
use App\Models\Concerns\GetNextSequenceValue;
use App\Models\Concerns\UsesActive;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property bool $phone_is_verify
 * @property string|null $avatar
 * @property string|null $telegram_chat_id
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon|null $last_visit_at
 * @property string $password
 * @property float $balance
 * @property bool $is_active
 * @property string $locale
 * @property array|null $options
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int $jwt
 * @property-read array $permission_names
 * @property-read array $role_names
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OAuthProvider[] $oauthProviders
 * @property-read int|null $oauth_providers_count
 * @property-read \App\Models\Operator $operator
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \App\Models\Webmaster $webmaster
 * @method static \Illuminate\Database\Eloquent\Builder|User active()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastVisitAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneIsVerify($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTelegramChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements
    HasLocalePreference,
    JWTSubject
    //    ,MustVerifyEmail
{
    use Notifiable;
    use HasRoles;
    use HasFactory;
    use UsesActive;
    use GetNextSequenceValue;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'phone',
        'phone_is_verify',
        'avatar',
        'telegram_chat_id',
        'email_verified_at',
        'last_visit_at',
        'balance',
        'is_active',
        'locale',
        'options',
        'password',
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
        'last_visit_at' => 'datetime',
        'is_active' => 'boolean',
        'options' => Json::class,
        'locale' => Locale::class,
        'phone_is_verify' => 'boolean',
        'balance' => 'float',
    ];

    /**
     * Set permissions guard to API by default
     *
     * @var string
     */
    //    protected $guard_name = 'api';

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            $model->password = \Illuminate\Support\Facades\Hash::make($model->password);
            $model->username = strtolower($model->username);
            $model->email = strtolower($model->email);
        });
        static::updating(function (self $model) {
            $model->username = strtolower($model->username);
            $model->email = strtolower($model->email);
        });
    }

    public function routeNotificationFor(string $driver)
    {
        if (method_exists($this, $method = 'routeNotificationFor' . Str::studly($driver))) {
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
    public function routeNotificationForMail(): string
    {
        return $this->email;
    }

    /**
     * Route notifications for the sms channel.
     *
     * @return string|null
     */
    public function routeNotificationForSms(): string
    {
        return $this->phone;
    }

    /**
     * Route notifications for the telegram channel.
     *
     * @return string|null
     */
    public function routeNotificationForTelegram(): string
    {
        return $this->telegram_chat_id;
    }

    /**
     * @return string|null
     */
    public function preferredLocale(): string
    {
        return $this->locale;
    }

    /**
     * The channels the user receives notification broadcasts on.
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }

    /**
     * Check valid email domain mx records
     */
    public function hasValidEmail(): bool
    {
        [$username, $domain] = explode('@', $this->email);

        return checkdnsrr($domain, 'MX');
    }

    /**
     * Get the oauth providers.
     */
    public function oauthProviders(): HasMany
    {
        return $this->hasMany(\App\Models\OAuthProvider::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }

    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function getRoleNamesAttribute(): array
    {
        return $this->getRoleNames()->toArray();
    }

    public function getPermissionNamesAttribute(): array
    {
        return $this->getPermissionNames()->toArray();
    }

    public function getJwtAttribute(): int
    {
        return $this->getJWTIdentifier();
    }

    /**
     * Check whether current role is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(Role::ROLE_ADMIN);
    }

    /**
     * Check whether current role is root
     */
    public function isRoot(): bool
    {
        return $this->hasRole(Role::ROLE_ROOT);
    }

    /**
     * Check whether current role is visitor
     */
    public function isClient(): bool
    {
        return $this->hasRole(Role::ROLE_CLIENT);
    }
}
