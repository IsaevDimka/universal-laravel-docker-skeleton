<?php

namespace App\Models;

/**
 * App\Models\Language
 *
 * @property int $id
 * @property string $short
 * @property string $long
 * @property string $english
 * @property string $native
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereEnglish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereNative($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Language active()
 */
class Language extends BaseModel
{
    protected $table = 'languages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'short',
        'long',
        'english',
        'native',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
    ];

    /**
     * Lowercase short attribute
     *
     * @param $value
     */
    public function setShortAttribute($value)
    {
        $this->attributes['short'] = strtolower($value);
    }

    /**
     * Scope a query to only include active locales.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
