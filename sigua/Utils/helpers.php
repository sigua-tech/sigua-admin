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

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Sigua\Admin\Models\Staff;
use Sigua\Utils\ImageProcess;

if (! function_exists('success')) {
    function success(array $data = [], mixed $message = ''): JsonResponse
    {
        $data['success'] = true;
        if ($message) {
            $data['message'] = $message;
        }
        return response()->json($data);
    }
}

if (! function_exists('fail')) {
    function fail(mixed $message, int $code = 1000, array $errors = [], int $statusCode = 418): JsonResponse
    {
        $data = [
            'success' => false,
            'code' => $code,
            'message' => $message,
        ];

        if ($errors) {
            $data['errors'] = $errors;
        }

        return response()->json($data, $statusCode);
    }
}

if (! function_exists('isMobile')) {
    function isMobile(string $input): bool
    {
        return (bool) preg_match('/^(?:(?:\\+|00)86)?1[3-9]\\d{9}$/', $input);
    }
}

if (! function_exists('isEmail')) {
    function isEmail(string $input): bool
    {
        return (bool) preg_match('/^[\\w\\-]+@[a-z0-9_\\-]+(\\.[a-z0-9_\\-]+)+$/i', $input);
    }
}

if (! function_exists('adminGuard')) {
    function adminGuard(): Guard|StatefulGuard
    {
        return auth('admin');
    }
}

if (! function_exists('adminUser')) {
    function adminUser(): Staff|null
    {
        return adminGuard()->user();
    }
}

if (! function_exists('inputs')) {
    function inputs(array $keys = null, mixed $defaults = null): array
    {
        $inputs = $keys ? request()->only($keys) : request()->all();

        if (is_null($defaults)) {
            return $inputs;
        }

        $keys || $keys = array_keys($inputs);
        foreach ($keys as $key => $val) {
            if (! is_null($val)) {
                continue;
            }
            if (is_array($defaults)) {
                $inputs[$key] = $defaults[$key] ?? null;
                continue;
            }
            $inputs[$key] = $defaults;
        }

        return $inputs;
    }
}

if (! function_exists('formatListData')) {
    function formatListData(LengthAwarePaginator $paginator): array
    {
        return [
            'path' => $paginator->path(),
            'items' => $paginator->items(),
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'current_page' => $paginator->currentPage(),
        ];
    }
}

if (! function_exists('thumb')) {
    /**
     * 生成缩略图。
     * 指定宽高则缩放后裁剪，只指定宽或高则等比缩小。
     * @param string $imageContent 原图内容
     * @param string $targetPath 小图保存路径
     * @throws Exception
     */
    function thumb(string $imageContent, string $targetPath, int $width, int $height): void
    {
        if (! $imageContent) {
            return;
        }
        $image = new ImageProcess($imageContent);
        Storage::disk('public')->put($targetPath, $image->thumb($width, $height));
    }
}

if (! function_exists('storageUrl')) {
    function storageUrl($path): string
    {
        return asset('storage/' . $path);
    }
}

if (! function_exists('isInAdmin')) {
    /**
     * 访问的 URI 是否是管理后台。
     */
    function isInAdmin(): bool
    {
        return request()->is('admin*');
    }
}

if (! function_exists('isInConsole')) {
    function isInConsole(): bool
    {
        return app()->runningInConsole();
    }
}

if (! function_exists('trimArray')) {
    function trimArray(array $data): array
    {
        return array_map(fn ($value) => is_string($value) ? trim($value) : $value, $data);
    }
}
