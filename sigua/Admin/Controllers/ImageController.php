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
use Sigua\Models\Image;

class ImageController extends Controller
{
    public function index()
    {
    }

    /**
     * @throws \Exception
     */
    public function save(Request $request)
    {
        if (! $request->hasFile('img')) {
            return $this->error('请选择上传图片');
        }

        $file = $request->file('img');
        if (! $file->isValid()) {
            return $this->error('非法的图片');
        }

        $type = request('type', 'shared');
        // shared 类型永久保存
        // 其它类型由程序控制保存（新建/覆盖/删除）
        if (! in_array($type, ['shared', 'album', 'avatar'])) {
            return $this->error('type 参数不正确');
        }

        $tempPath = $file->path();
        // 文件是否已存在
        $hash = sha1_file($tempPath);

        if ($type === 'shared') {
            $image = Image::query()
                ->where('hash', $hash)
                ->where('type', 'shared')
                ->first('path');
            if ($image) {
                return $this->success($image->path);
            }
        }

        $dir = "images/{$type}/" . date('Ym/d');
        $path = $file->storePubliclyAs($dir, $file->hashName(), ['disk' => 'public']);

        // 保存记录
        Image::create([
            'path' => $path,
            'hash' => $hash,
            'size' => filesize($tempPath),
            'type' => $type,
            'module' => request('module', ''),
            'staff_id' => adminUser()->id,
        ]);

        $imageContent = $file->getContent();

        if ($type === 'avatar') {
            $thumbSizes = [
                'lg' => [800, 800],
                'sm' => [200, 200],
            ];
        } else {
            // TODO 可在配置文件设置尺寸
            $thumbSizes = [
                'lg' => [800, 0],
                'md' => [400, 400],
                'sm' => [200, 200],
                'tn' => [100, 100],
            ];
        }
        $this->saveThumbs($thumbSizes, $imageContent, $path);
        return $this->success($path);
    }

    /**
     * @throws \Exception
     */
    private function saveThumbs(array $sizes, string $imageContent, string $orgPath)
    {
        foreach ($sizes as $thumbType => $size) {
            $thumbPath = $this->thumbPath($orgPath, $thumbType);
            thumb($imageContent, $thumbPath, $size[0], $size[1]);
        }
    }

    private function thumbPath($orgPath, $type): string
    {
        return $orgPath . '-' . $type . '.jpg';
    }

    private function storeStaffAvatar($file, $dir)
    {
        return $file->storePubliclyAs($dir, adminUser()->id . '.jpg', ['disk' => 'public']);
    }

    private function success($path): JsonResponse
    {
        return success([
            'errno' => 0, // WangEditor 要求返回
            'data' => [
                'org' => storageUrl($path), // 原图
                'url' => storageUrl($this->thumbPath($path, 'lg')), // WangEditor 要求返回
                'thumb' => storageUrl($this->thumbPath($path, 'sm')),
            ],
        ]);
    }

    private function error(mixed $message, int $errno = 1): JsonResponse
    {
        return response()->json([
            'errno' => $errno,
            'message' => $message,
        ], 418);
    }
}
