<?php
/**
 * 丝瓜管理后台（Sigua admin）
 * 一个基于 Laravel 的管理后台系统，让中后台开发更简单！
 *
 * @author    Yiba <yibafun@gmail.com>
 * @copyright sigua.tech
 * @license   MIT (http://opensource.org/licenses/MIT)
 */
declare(strict_types=1);

namespace Sigua\Article\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Sigua\Admin\Models\Staff;
use Sigua\Models\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $brief
 * @property Detail $detail
 * @property int $asc_num
 * @property int $desc_num
 * @property array $album
 * @property array $tag_ids
 * @property array $cat_ids 商品分类 id，需手动 append
 * @property BelongsToMany $tags
 * @property BelongsToMany $categories
 * @property Detail $detailItem
 * @property HasOne $creator
 */
class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'album',
        'brief',
        'status',
        'asc_num',
        'desc_num',
        'creator_id',
        'author',
        'source',
        'editor',
    ];

    protected $casts = [
        'album' => 'array',
        'asc_num' => 'int',
        'desc_num' => 'int',
        'creator_id' => 'int',
    ];

    protected $appends = [
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tag_pivots', 'article_id', 'tag_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'article_category_pivots', 'article_id', 'category_id');
    }

    public function catIds(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->categories ? array_column($this->categories->toArray(), 'id') : [],
        );
    }

    public function tagIds(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tags ? array_column($this->tags->toArray(), 'id') : [],
        );
    }

    public function detailItem(): HasOne
    {
        return $this->hasOne(Detail::class, 'id', 'id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'creator_id');
    }
}
