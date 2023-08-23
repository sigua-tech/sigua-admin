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

namespace Sigua\Article\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Sigua\Article\Models\Article;
use Sigua\Article\Models\Category;
use Sigua\Article\Models\CategoryPivot;
use Sigua\Article\Requests\ArticleRequest;
use Sigua\Article\Services\ArticleService;

class ArticleController extends Controller
{
    public function index(): JsonResponse
    {
        $query = Article::query()
            ->orderByDesc('desc_num')
            ->orderByDesc('id')
            ->with(['tags', 'categories']);

        if ($cid = request('cat_id')) {
            $query->whereIn(
                'id',
                CategoryPivot::query()->where('category_id', $cid)->get('article_id')
            );
        }

        if ($keyword = request('keyword')) {
            $query->where(function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('brief', 'like', "%{$keyword}%");
            });
        }

        return success([
            'listData' => formatListData($query->paginate(20)),
            'categories' => Category::childrenTree(),
        ]);
    }

    public function show(Article $article): JsonResponse
    {
        $article->append(['cat_ids'])
            ->load(['tags', 'creator']);
        $article->detail = $article->detailItem->detail;
        $article = $article->toArray();
        unset($article['detail_item']);
        return success([
            'item' => $article,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function create(ArticleRequest $request): JsonResponse
    {
        ArticleService::create(
            $this->getInputs(),
            request('detail'),
            request('tags', []),
            request('cat_ids', []),
        );

        return success();
    }

    /**
     * @throws \Exception
     */
    public function update(ArticleRequest $request, Article $article): JsonResponse
    {
        ArticleService::update(
            $article,
            $this->getInputs(),
            request('detail'),
            request('tags', []),
            request('cat_ids', []),
        );

        return success();
    }

    public function delete(Article $article): JsonResponse
    {
        return success('TODO');
    }

    private function getInputs(): array
    {
        return inputs([
            'title',
            'album',
            'brief',
            'asc_num',
            'status',
            'author',
            'source',
            'editor',
        ]);
    }
}
