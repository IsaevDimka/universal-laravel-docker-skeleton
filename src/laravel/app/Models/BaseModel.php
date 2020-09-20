<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BaseModel query()
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    public const FORMAT_DATETIME = 'Y-m-d H:i:s';

    /**
     * Get table name
     *
     * @return string|null
     */
    public static function getTableName() : string
    {
        return with(new static)->getTable();
    }

    public static function formattingCarbonAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(BaseModel::FORMAT_DATETIME) : null;
    }
}
