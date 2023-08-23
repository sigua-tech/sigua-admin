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

use Illuminate\Database\Eloquent\Relations\Pivot;

class StaffRolePivot extends Pivot
{
    public $timestamps = false;

    protected $table = 'staff_role_pivots';

    protected $fillable = [
        'staff_id',
        'role_id',
    ];

    protected $casts = [
        'staff_id' => 'integer',
        'role_id' => 'integer',
    ];
}
