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

namespace Sigua\Admin\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Sigua\Models\Model;
use Sigua\Utils\ChildrenTree;

/**
 * @property int $id
 * @property string $title
 * @property string $brief
 * @property string $permissions
 * @property int $asc_num
 * @property int $staff_count
 * @property StaffDepartment $parent
 * @property HasMany $staffs
 */
class StaffDepartment extends Model
{
    //    use HasFactory;

    protected $fillable = [
        'parent_id',
        'title',
        'brief',
        'asc_num',
        'staff_count',
    ];

    protected $attributes = [
        'parent_id' => null,
        'brief' => '',
        'asc_num' => 9,
        'staff_count' => 0,
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'asc_num' => 'integer',
        'staff_count' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function staffs(): HasMany
    {
        return $this->hasMany(Staff::class, 'role_id');
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
        $disabled = $disabledId ? ['id' => $disabledId] : [];
        return ChildrenTree::make(static::all()->toArray(), $disabled);
    }
}
