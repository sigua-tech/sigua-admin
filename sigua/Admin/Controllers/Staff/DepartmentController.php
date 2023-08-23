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
use Sigua\Admin\Models\StaffDepartment;

class DepartmentController extends Controller
{
    public function index(): JsonResponse
    {
        return success([
            'items' => StaffDepartment::childrenTree(),
        ]);
    }

    public function create(Request $request): JsonResponse
    {
        $inputs = $this->validatedInputs($request);
        StaffDepartment::create($inputs);

        return success([
            'options' => StaffDepartment::childrenTree(),
        ]);
    }

    public function show(StaffDepartment $department): JsonResponse
    {
        if (request('with_parent')) {
            $department->parent;
        }

        $data = ['item' => $department];
        if (request('get_parent_options')) {
            $data['options'] = StaffDepartment::childrenTree($department->id);
        }

        return success($data);
    }

    public function update(Request $request, StaffDepartment $department): JsonResponse
    {
        $inputs = $this->validatedInputs($request);
        $department->update($inputs);

        // 返回更新后的列表
        return success([
            'options' => StaffDepartment::childrenTree($department->id),
        ]);
    }

    public function validatedInputs(Request $request): array
    {
        $request->validate(
            ['title' => 'required'],
            ['title.required' => '请输入部门名称']
        );

        $inputs = inputs(['title', 'parent_id', 'brief', 'asc_num']);
        empty($inputs['parent_id']) && $inputs['parent_id'] = null; // 0、'' => null

        return $inputs;
    }

    public function destroy(StaffDepartment $department): JsonResponse
    {
        // 有员工，不允许删除
        abort_if($department->staff_count > 0, 406, '部门下有员工，不允许删除');

        // 有子分类，不允许删除
        $hasChild = StaffDepartment::query()
            ->where('parent_id', $department->id)
            ->exists();
        abort_if($hasChild, 406, '该部门有子部门，不允许删除');

        $department->delete();

        return success();
    }
}
