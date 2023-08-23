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

namespace Sigua\Admin\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sigua\Admin\Models\StaffRole;
use Sigua\Admin\Models\StaffRolePivot;
use Sigua\Admin\Services\StaffPermissionService;

class RoleController extends Controller
{
    public function index(): JsonResponse
    {
        $data = ['items' => StaffRole::all()];

        if (request('get_permissions')) {
            $data['permissions'] = StaffPermissionService::getPermissionTree();
        }

        return success($data);
    }

    public function create(Request $request): JsonResponse
    {
        StaffRole::create($this->validatedInputs());

        return success();
    }

    public function show(StaffRole $role): JsonResponse
    {
        return success([
            'item' => $role,
        ]);
    }

    public function update(Request $request, StaffRole $role): JsonResponse
    {
        $role->update($this->validatedInputs());
        return success();
    }

    public function destroy(StaffRole $role): JsonResponse
    {
        $hasStaff = StaffRolePivot::query()->where('role_id', $role->id)->exists();
        abort_if($hasStaff, 406, '该角色下有员工，不能删除');

        $role->delete();
        return success([
            'items' => StaffRole::all(),
        ]);
    }

    private function validatedInputs(): array
    {
        request()->validate(
            ['title' => 'required'],
            ['title.required' => '请输入角色名称']
        );
        return inputs(['title', 'brief', 'permissions', 'asc_num', 'is_show', 'enabled', 'type']);
    }
}
