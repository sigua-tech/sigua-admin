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

namespace Sigua\Admin\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Sigua\Admin\Models\Staff;
use Sigua\Admin\Models\StaffDepartment;
use Sigua\Admin\Models\StaffRole;
use Sigua\Admin\Models\StaffRolePivot;
use Sigua\Admin\Requests\StaffRequest;
use Sigua\Admin\Services\StaffService;

class StaffController extends Controller
{
    public function index(): Factory|View|Application|JsonResponse
    {
        $keyword = request('keyword');
        $roleId = (int) request('role_id');
        $departmentId = (int) request('department_id');

        $query = Staff::query()
            ->orderByDesc('id')
            ->with(['roles', 'department']);

        if ($roleId) {
            $query->whereIn(
                'id',
                StaffRolePivot::query()->select('staff_id')->where('role_id', $roleId)
            );
        }

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        if ($keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('mobile', 'like', "%{$keyword}%")
                    ->orWhere('realname', 'like', "%{$keyword}%");
            });
        }

        $paginator = $query->paginate(15);
        $data = [
            'listData' => formatListData($paginator),
        ];

        if (request('get_roles')) {
            $data['roles'] = StaffRole::all();
        }
        if (request('get_departments')) {
            $data['departments'] = StaffDepartment::childrenTree();
        }

        return success($data);
    }

    public function create(StaffRequest $request): JsonResponse
    {
        StaffService::create($this->getInputs(), request('role_ids', []));

        return success();
    }

    public function show(Staff $staff): JsonResponse
    {
        $staff->department;
        $staff->roles;

        $data = ['item' => $staff];

        if (request('get_roles')) {
            $data['roles'] = StaffRole::all();
        }

        return success($data);
    }

    public function update(StaffRequest $request, Staff $staff): JsonResponse
    {
        // 只有超级管理员才能管理超级管理员
        if ($staff->is_super && ! adminUser()->is_super) {
            abort(406, '你无权编辑超级管理员');
        }

        StaffService::update($staff, $this->getInputs(), request('role_ids', []));

        return success();
    }

    public function destroy(Staff $staff): JsonResponse
    {
        abort_if($staff->is_super, 406, '不能删除超级管理员');

        DB::transaction(function () use ($staff) {
            $staff->roles()->detach();
            $staff->delete();
        });

        return success();
    }

    public function batDelete(): JsonResponse
    {
        $ids = request('ids');
        abort_if(! $ids, 406, '请求参数错误');

        Staff::query()
            ->whereNot('id', config('sigua.super_id'))
            ->whereIn('id', $ids)
            ->delete();

        return success(compact('ids'));
    }

    private function getInputs(): array
    {
        return inputs(['avatar', 'name', 'password', 'email', 'mobile', 'realname', 'enabled', 'department_id']);
    }
}
