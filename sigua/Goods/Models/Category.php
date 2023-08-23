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

namespace Sigua\Goods\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sigua\Models\Model;
use Sigua\Utils\ChildrenTree;

/**
 * @property int $id
 * @property int $parent_id
 * @property int $asc_num
 * @property int $goods_count
 * @property bool $is_show
 * @property string $brief
 * @property static $parent
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'goods_categories';

    protected $fillable = ['parent_id', 'title', 'brief', 'asc_num', 'is_show'];

    protected $attributes = [
        'parent_id' => null,
        'asc_num' => 99,
        'goods_count' => 0,
        'is_show' => 1,
        'brief' => '',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'asc_num' => 'integer',
        'goods_count' => 'integer',
        'is_show' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public static function all($columns = ['*']): Collection|array
    {
        return static::query()
            ->orderBy('asc_num')
            ->orderBy('id')
            ->get($columns);
    }

    public static function childrenTree(int $disabledId = 0): array
    {
        $items = static::all(['id', 'parent_id', 'asc_num', 'title', 'brief', 'is_show', 'goods_count', 'created_at', 'updated_at'])
            ->toArray();
        $disabled = $disabledId ? ['id' => $disabledId] : [];
        return ChildrenTree::make($items, $disabled);
    }

    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Goods::class, 'goods_category_pivots', 'category_id', 'goods_id');
    }
}
