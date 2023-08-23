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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sigua\Models\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $brief
 * @property string $permissions
 * @property int $asc_num
 * @property bool $enabled
 * @property int $staff_count
 * @property string $type
 */
class StaffRole extends Model
{
    //    use HasFactory;

    protected $table = 'staff_roles';

    protected $fillable = [
        'title',
        'brief',
        'permissions',
        'asc_num',
        'enabled',
        'type',
    ];

    protected $attributes = [
        'brief' => '',
        'permissions' => '[]',
        'asc_num' => 9,
        'enabled' => 1,
    ];

    protected $casts = [
        'permissions' => 'array',
        'asc_num' => 'integer',
        'enabled' => 'boolean',
    ];

    public function staffs(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'staff_role_pivots', 'role_id', 'staff_id');
    }

    public static function all($columns = ['*']): Collection|array
    {
        return static::query()
            ->orderBy('asc_num')
            ->orderBy('id')
            ->get($columns);
    }
    /*
        protected function permissions(): Attribute
        {
            return Attribute::make(
                get: fn ($value) => is_null($value) ? [] : json_decode($value, false, 512, JSON_THROW_ON_ERROR),
            );
        }*/
}
