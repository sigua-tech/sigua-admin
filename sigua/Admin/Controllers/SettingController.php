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
use Sigua\Models\Setting;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $group = request('group');
        $query = Setting::query();
        if ($group) {
            $query->where('group', $group);
        }

        return success([
            'items' => $query->get()->all(),
        ]);
    }

    public function save(): JsonResponse
    {
        $settings = request('settings');
        foreach ($settings as $key => $value) {
            Setting::query()->where('key', $key)->update([
                'value' => $value,
            ]);
        }
        return success();
    }
}
