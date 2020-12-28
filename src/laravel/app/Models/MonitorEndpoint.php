<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class MonitorEndpoint extends BaseModel
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
        'latest_http_code'  => 'integer',
    ];
}
