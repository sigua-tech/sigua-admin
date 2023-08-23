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

namespace Sigua\Utils;

class TagHelper
{
    public static function parseTagIds($tags, $tagModel): array
    {
        $tagIds = [];
        foreach ($tags as $tag) {
            if (! empty($tag['id'])) {
                $tagIds[] = $tag['id'];
                continue;
            }
            if (empty($tag['title'])) {
                continue;
            }
            $tagItem = $tagModel::query()
                ->where('title', $tag['title'])
                ->first(['id']);
            if (! $tagItem) {
                $tagItem = $tagModel::create(['title' => $tag['title']]);
            }
            $tagIds[] = $tagItem['id'];
        }

        return $tagIds;
    }
}
