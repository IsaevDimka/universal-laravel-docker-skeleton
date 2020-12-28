<?php

namespace App\Models;

/**
 * App\Models\Timezone
 *
 * @property int $id
 * @property string $timezone
 * @property string $name
 * @property int $offset
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone whereDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone whereOffset($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Timezone whereUpdatedAt($value)
 * @mixin \Illuminate\Database\Eloquent\
 * @method static \Illuminate\Database\Eloquent\Builder|Timezone whereTimezone($value)
 */
class Timezone extends BaseModel
{
    protected $table = 'timezones';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'timezone',
        'name',
        'offset',
    ];
}
