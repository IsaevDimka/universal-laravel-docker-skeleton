<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Currency
 *
 * @property int $id
 * @property string $name
 * @property string $sign
 * @property bool $is_active
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Currency withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Currency withoutTrashed()
 * @mixin \Illuminate\Database\Eloquent\
 * @property bool $isActive
 * @property-read mixed $createdAt
 * @property-read mixed $updatedAt
 */
class Currency extends BaseModel
{

    protected $table = 'currencies';

    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'sign',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
    ];

    /**
     * Приведение sign к верхнему регистру
     * Make a string uppercase
     *
     * @param $value
     */
    public function setSignAttribute($value)
    {
        $this->attributes['sign'] = \mb_strtoupper($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return BaseModel::formattingCarbonAttribute($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return BaseModel::formattingCarbonAttribute($value);
    }
}
