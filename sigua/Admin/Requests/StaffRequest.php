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

namespace Sigua\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    //    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'min:2',
                'regex:/^[a-z][0-9a-z]+$/i',
            ],
            'realname' => 'nullable|min:2',
            'mobile' => [
                'nullable',
                'regex:/^1[3-9]\\d{9}$/',
            ],
            'email' => [
                'nullable',
                'email',
            ],
        ];

        if ($this->isMethod('POST') || ! empty(request('password'))) {
            // 新建或修改密码时，验证密码规则
            $rules['password'] = 'required|min:6|confirmed';
        }

        if ($this->isMethod('PUT')) {
            $staff = $this->route('staff');
            $rules['name'][] = 'unique:staffs,name,' . $staff->id;
            $rules['mobile'][] = 'unique:staffs,mobile,' . $staff->id;
            $rules['email'][] = 'unique:staffs,email,' . $staff->id;
        } else {
            $rules['name'][] = 'unique:staffs';
            $rules['mobile'][] = 'unique:staffs';
            $rules['email'][] = 'unique:staffs';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => '请输入用户名',
            'name.min' => '用户名长度不能小于 :min 位',
            'name.regex' => '用户名只能以字母开头，由字母和数字组成',
            'name.unique' => '用户名已被使用',
            'password.required' => '请输入密码',
            'password.min' => '密码长度不能小于 :min 位',
            'password.confirmed' => '两次输入密码不一致',
            'mobile.regex' => '手机号码格式不正确',
            'mobile.unique' => '手机号码已被使用',
            'email.email' => '邮箱格式不正确',
            'email.unique' => '邮箱已被使用',
        ];
    }
}
