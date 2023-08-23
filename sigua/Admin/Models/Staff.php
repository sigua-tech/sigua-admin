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

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $avatar
 * @property string $email
 * @property string $password
 * @property string $mobile
 * @property string $realname
 * @property bool $is_super 是否是超级管理员
 * @property bool $enabled 是否启用
 * @property string $remember_token
 * @property BelongsToMany $roles
 * @property array $role_ids
 * @property StaffDepartment $department
 * @property array $permissions
 */
class Staff extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $table = 'staffs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
        'mobile',
        'realname',
        'remember_token',
        'enabled',
        'department_id',
    ];

    protected $attributes = [
        'avatar' => '',
        'email' => '',
        'mobile' => '',
        'realname' => '',
        'enabled' => 1,
        'department_id' => null,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected $appends = [
        'role_ids',
        'permissions',
        'is_super',
        'show_name',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(StaffDepartment::class, 'department_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(StaffRole::class, 'staff_role_pivots', 'staff_id', 'role_id');
    }

    public function isSuper(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->id === (int) config('sigua.super_id')
        );
    }

    public function showName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->realname ?: $this->name
        );
    }

    public function roleIds(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->roles ? array_column($this->roles->toArray(), 'id') : [],
        );
    }

    public function permissions(): Attribute
    {
        return Attribute::make(get: function () {
            if ($this->is_super) {
                // 超级管理员拥有全部权限
                return ['*'];
            }

            // 从角色获取授权列表
            if (! $roles = $this->roles->toArray()) {
                return [];
            }

            $permissions = array_merge(...array_column($roles, 'permissions'));
            $permissions = array_unique($permissions);
            if (in_array('*', $permissions)) {
                return ['*'];
            }
            return $permissions;
        });
    }
}
