<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @package App\Models
 * @mixin \Illuminate\Database\Eloquent\Model
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 */
class BaseModel extends Model
{
    public const DEFAULT_PER_PAGE = 25;
    public const FORMAT_DATETIME = Carbon::DEFAULT_TO_STRING_FORMAT;

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
