<?php

namespace App\Models;

/**
 * App\Models\Locale
 *
 * @property int $id
 * @property string $sign
 * @property string $name
 * @property bool $is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Locale active()
 */
class Locale extends BaseModel
{
    protected $table = 'locales';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'sign',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Lowercase sign attribute
     *
     * @param $value
     */
    public function setSignAttribute($value)
    {
        $this->attributes['sign'] = strtolower($value);
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
