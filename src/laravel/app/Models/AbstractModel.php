<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractModel
 *
 * @package App\Models
 * @method static \Illuminate\Database\Eloquent\Builder|AbstractModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbstractModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AbstractModel query()
 * @mixin \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
 */
abstract class AbstractModel extends Model
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
