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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function chpw(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'password' => 'required|min:6|confirmed',
        ]);

        $account = adminUser();

        $password = request('password');
        if (! Hash::check(request('old_password'), $account->password)) {
            return fail('旧密码不正确');
        }

        $account->forceFill([
            'password' => Hash::make($password),
        ])->setRememberToken(Str::random(60));

        $account->save();

        return success();
    }

    public function chProfile(Request $request): JsonResponse
    {
        $account = adminUser();
        $messages = [
            'mobile.unique' => '该手机号已被使用',
            'email.unique' => '该邮箱已被使用',
        ];
        $inputs = $request->validate([
            'avatar' => 'nullable',
            'realname' => 'nullable|min:2',
            'mobile' => 'nullable|regex:/^1[3-9]\\d{9}$/|unique:staffs,mobile,' . $account->id,
            'email' => 'nullable|email|unique:staffs,email,' . $account->id,
        ]);

        $account->fill($inputs)
            ->save();

        return success([
            'userInfo' => $account->refresh()->toArray(),
        ]);
    }
}
