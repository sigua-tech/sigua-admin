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

namespace Sigua\Article\Services;

use Illuminate\Support\Facades\DB;
use Sigua\Article\Models\Article;
use Sigua\Article\Models\Category;
use Sigua\Article\Models\Tag;
use Sigua\Utils\TagHelper;

class ArticleService
{
    /**
     * @throws \Exception
     */
    public static function create(array $attributes, string $detail, array $tags, array $catIds): Article
    {
        $attributes = static::preprocess($attributes, $detail);
        $attributes['creator_id'] = adminUser()->id;

        try {
            DB::beginTransaction();
            /* @var Article $article */
            $article = Article::create($attributes);
            $article->detailItem()->create([
                'id' => $article->id,
                'detail' => $detail,
            ]);

            // 关联 tag
            $tagIds = TagHelper::parseTagIds($tags, Tag::class);
            if ($tagIds) {
                $article->tags()->attach($tagIds);
                static::updateTagsArticleCount($tagIds);
            }

            // 关联 分类
            if ($catIds) {
                $article->categories()->attach($catIds);
                static::updateCatsArticleCount($tagIds);
            }

            DB::commit();
            return $article;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Exception
     */
    public static function update(Article $article, array $attributes, string $detail, array $tags, array $catIds): void
    {
        $attributes = static::preprocess($attributes, $detail);
        $oldCatIds = $article->cat_ids;
        $oldTagIds = $article->tag_ids;
        try {
            DB::beginTransaction();
            $article->update($attributes);
            $article->detailItem()->update(['detail' => $detail]);

            // 关联 tag
            $tagIds = TagHelper::parseTagIds($tags, Tag::class);
            $article->tags()->sync($tagIds);
            $tids = array_unique(array_merge($tagIds, $oldTagIds));
            static::updateTagsArticleCount($tids);

            // 关联 分类
            $article->categories()->sync($catIds);
            $cids = array_unique(array_merge($catIds, $oldCatIds));
            static::updateCatsArticleCount($cids);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private static function preprocess(array $attributes, string $detail): array
    {
        $attributes = trimArray($attributes);
        $attributes['desc_num'] = 10000 - $attributes['asc_num'];
        if (empty($attributes['brief'])) {
            $attributes['brief'] = mb_substr(strip_tags($detail), 0, 100);
        }
        return $attributes;
    }

    private static function updateTagsArticleCount($tagIds): void
    {
        if (! $tagIds) {
            return;
        }

        $tagItems = Tag::find($tagIds);
        foreach ($tagItems as $tag) {
            /* @var Tag $tag */
            $tag->forceFill([
                'article_count' => $tag->articles()->count('article_id'),
            ])->save();
        }
    }

    private static function updateCatsArticleCount($catIds): void
    {
        if (! $catIds) {
            return;
        }

        $catItems = Category::find($catIds);
        foreach ($catItems as $cat) {
            /* @var Category $cat */
            $cat->forceFill([
                'article_count' => $cat->articles()->count('article_id'),
            ])->update();
        }
    }
}
