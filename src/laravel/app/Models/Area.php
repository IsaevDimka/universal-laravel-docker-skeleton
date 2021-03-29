<?php

declare(strict_types=1);

namespace App\Models;

/**
 * App\Models\Area
 *
 * @property-read \App\Models\Region|null $region
 * @method static \Illuminate\Database\Eloquent\Builder|Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Area query()
 * @mixin \Eloquent
 * @mixin IdeHelperArea
 * @property int $id
 * @property string $code
 * @property string $name
 * @property bool $is_active
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Area whereName($value)
 */
class Area extends AbstractModel
{
    public $timestamps = false;

    protected $table = 'areas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function region()
    {
        return $this->hasOne(Region::class);
    }
}
