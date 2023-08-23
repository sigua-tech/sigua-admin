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

namespace Sigua\Goods\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Sigua\Goods\Models\SkuValue;

class SkuValueController extends Controller
{
    public function index(): JsonResponse
    {
        if (! $nameId = request('name_id')) {
            return fail('name_id 参数是必须的');
        }

        $items = SkuValue::query()
            ->where('name_id', $nameId)
            ->get(['id', 'title'])
            ->all();

        return success(compact('items'));
    }
}
