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

namespace Sigua\Article\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required',
            'asc_num' => 'between:0,9999',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => '请输入标题',
            'asc_num.between' => '显示排序必须在 :min - :max 之间',
        ];
    }
}
