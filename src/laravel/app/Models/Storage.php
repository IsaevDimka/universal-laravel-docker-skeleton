<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Storage
 *
 * @property int $id
 * @property string $filename
 * @property string $type
 * @property string $path
 * @property int $size
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read true|false $url
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Storage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Storage newQuery()
 * @method static \Illuminate\Database\Query\Builder|Storage onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Storage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Storage withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Storage withoutTrashed()
 * @mixin \Eloquent
 * @property int|null $userId
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @mixin IdeHelperStorage
 */
class Storage extends AbstractModel
{
    use SoftDeletes;

    public const PUBLIC_PATH = '/storage/';

    public const STORAGE_PATH = 'public';

    protected $perPage = 50;

    protected $table = 'storages';

    protected $primaryKey = 'id';

    protected $fillable = [
        'filename',
        'type',
        'path',
        'size',
        'user_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    /**
     * url
     * @return true|false
     */
    public function getUrlAttribute()
    {
        return url()->to($this->path);
    }
}
