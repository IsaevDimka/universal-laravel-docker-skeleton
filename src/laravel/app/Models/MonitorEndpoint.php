<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\MonitorEndpoint
 *
 * @property int $id
 * @property string $app
 * @property string $name
 * @property string $url
 * @property int|null $latest_http_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint newQuery()
 * @method static \Illuminate\Database\Query\Builder|MonitorEndpoint onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint query()
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereLatestHttpCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MonitorEndpoint whereUrl($value)
 * @method static \Illuminate\Database\Query\Builder|MonitorEndpoint withTrashed()
 * @method static \Illuminate\Database\Query\Builder|MonitorEndpoint withoutTrashed()
 * @mixin \Eloquent
 * @mixin IdeHelperMonitorEndpoint
 */
class MonitorEndpoint extends AbstractModel
{
    use SoftDeletes;

    protected $table = 'monitor_endpoints';

    protected $primaryKey = 'id';

    protected $fillable = [
        'app',
        'name',
        'url',
        'latest_http_code',
    ];

    protected $casts = [
        'latest_http_code' => 'integer',
    ];
}
