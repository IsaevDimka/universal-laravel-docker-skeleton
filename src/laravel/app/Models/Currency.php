<?php

declare(strict_types=1);

namespace App\Models;

/**
 * App\Models\Currency
 *
 * @property int                             $id
 * @property string                          $name
 * @property string                          $iso_code
 * @property bool                            $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Currency onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereIsoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Currency withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Currency withoutTrashed()
 * @mixin \Illuminate\Database\Eloquent\
 * @property bool                            $isActive
 * @property-read mixed                      $createdAt
 * @property-read mixed                      $updatedAt
 * @mixin IdeHelperCurrency
 */
class Currency extends BaseModel
{
    public $timestamps = false;

    protected $table = 'currencies';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'iso_code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'iso_code';
    }

    /**
     * Приведение sign к верхнему регистру
     * Make a string uppercase
     *
     * @param $value
     */
    public function setIsoCodeAttribute($value)
    {
        $this->attributes['iso_code'] = \mb_strtoupper($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return self::formattingCarbonAttribute($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return self::formattingCarbonAttribute($value);
    }

    public static function rules(): array
    {
        return [
            'name' => ['nullable', 'string'],
            'iso_code' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
