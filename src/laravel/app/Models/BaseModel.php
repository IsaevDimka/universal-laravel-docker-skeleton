<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 *
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @mixin \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
 * @mixin IdeHelperBaseModel
 */
class BaseModel extends Model
{
    public const FORMAT_DATETIME = Carbon::DEFAULT_TO_STRING_FORMAT;

    protected $perPage = 10;

    /**
     * Get table name
     *
     * @return string|null
     */
    public static function getTableName(): string
    {
        return with(new static())->getTable();
    }

    public static function formattingCarbonAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(self::FORMAT_DATETIME) : null;
    }
}
