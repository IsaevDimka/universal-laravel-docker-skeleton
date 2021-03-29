<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\News
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int $author_id
 * @property bool $is_active
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $author
 * @method static \Illuminate\Database\Eloquent\Builder|News newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|News newQuery()
 * @method static \Illuminate\Database\Query\Builder|News onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|News query()
 * @method static \Illuminate\Database\Eloquent\Builder|News whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|News whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|News withTrashed()
 * @method static \Illuminate\Database\Query\Builder|News withoutTrashed()
 * @mixin \Eloquent
 * @property int $authorId
 * @property bool $isActive
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property \Illuminate\Support\Carbon|null $deletedAt
 * @mixin IdeHelperNews
 */
class News extends AbstractModel
{
    use SoftDeletes;

    protected $table = 'news';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'author_id',
        'is_active',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }
}
