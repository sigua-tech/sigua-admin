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

use Sigua\Admin\Models\Staff;
use Sigua\Utils\ChildrenTree;

class StaffPermissionService
{
    /**
     * 当前用户是否有权限访问功能.
     */
    public static function can(Staff $staff, string $ability): bool
    {
        // 账号已停用
        abort_if(! $staff->enabled, 403, '该账号已停用');

        if ($staff->is_super) {
            // 超级管理员有全部权限
            return true;
        }

        return in_array($ability, $staff->permissions, true);
    }

    public static function getPermissionTree(): array
    {
        $options = config('permissions');

        $items = [];
        foreach ($options as $key => $option) {
            $lastDotPos = strrpos($key, '.');
            $items[] = [
                'name' => $key,
                'parent_name' => $lastDotPos ? substr($key, 0, $lastDotPos) : '',
                'title' => $option['title'],
            ];
            if (empty($option['items'])) {
                continue;
            }
            foreach ($option['items'] as $action => $title) {
                $items[] = [
                    'name' => "{$key}.{$action}",
                    'parent_name' => $key,
                    'title' => $title,
                ];
            }
        }

        return ChildrenTree::make($items, props: [
            'id' => 'name',
            'parent_id' => 'parent_name',
        ]);
    }
}
