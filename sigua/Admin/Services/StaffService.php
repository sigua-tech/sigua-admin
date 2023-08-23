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

namespace Sigua\Admin\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Sigua\Admin\Models\Staff;

class StaffService
{
    /**
     * @throws \Exception
     */
    public static function create(array $attributes, array $roleIds): Staff
    {
        $attributes = static::castInputs($attributes);
        $attributes['password'] = Hash::make($attributes['password']);
        $attributes['remember_token'] = Str::random(60);

        try {
            DB::beginTransaction();
            /**
             * @var Staff $staff
             */
            $staff = Staff::create($attributes);
            // 员工关联角色
            $roleIds && $staff->roles()->attach($roleIds);
            DB::commit();
            return $staff;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function update(Staff $staff, array $attributes, array $roleIds): void
    {
        $attributes = static::castInputs($attributes);
        if (! empty($attributes['password'])) {
            $attributes['password'] = Hash::make($attributes['password']);
        }

        DB::transaction(static function () use ($staff, $attributes, $roleIds) {
            $staff->update($attributes);
            // `员工-角色`关联同步
            $staff->roles()->sync($roleIds);
        });
    }

    private static function castInputs($attributes)
    {
        $attributes = array_map('trim', $attributes);
        $attributes['name'] = strtolower($attributes['name']);
        $attributes['enabled'] = (int) $attributes['enabled'];
        $attributes['department_id'] = $attributes['department_id'] ?: null; // 外键默认值是 null

        return $attributes;
    }
}
