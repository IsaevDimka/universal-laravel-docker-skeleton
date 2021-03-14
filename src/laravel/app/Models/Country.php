<?php

declare(strict_types=1);

namespace App\Models;

/**
 * App\Models\Country
 *
 * @property int                             $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property bool                            $isActive
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsActive($value)
 * @property bool                            $is_active
 * @mixin IdeHelperCountry
 * @property string $name_common
 * @property string $name_official
 * @property string $iso_code
 * @property array $raw
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereIsoCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNameCommon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereNameOfficial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Country whereRaw($value)
 */
class Country extends BaseModel
{
    public $timestamps = false;

    protected $table = 'countries';

    protected $primaryKey = 'id';

    protected $perPage = 250;

    protected $fillable = [
        'name_common',
        'name_official',
        'iso_code',
        'raw',
        'is_active',
    ];

    protected $casts = [
        'raw' => 'array',
        'is_active' => 'boolean',
    ];

    public static function rules(): array
    {
        return [
            'name_common' => ['nullable', 'string'],
            'name_official' => ['nullable', 'string'],
            'iso_code' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
