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
use Sigua\Goods\Models\Category;
use Sigua\Goods\Models\CategoryPivot;
use Sigua\Goods\Models\Goods;
use Sigua\Goods\Requests\GoodsRequest;
use Sigua\Goods\Services\GoodsService;

class GoodsController extends Controller
{
    public function index(): JsonResponse
    {
        $query = Goods::query()
            ->orderByDesc('desc_num')
            ->orderByDesc('id')
            ->with(['tags', 'categories'/* , 'skus', 'skuNames', 'skuValues' */]);

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

    public function show(Goods $goods): JsonResponse
    {
        $goods->append(['cat_ids', 'skus_formatted']);
        $goods->load('tags');
        return success([
            'item' => $goods,
        ]);
    }

    /**
     * @throws \Exception
     */
    public function create(GoodsRequest $request): JsonResponse
    {
        GoodsService::create(
            $this->getInputs(),
            request('tags', []),
            request('cat_ids', []),
            request('skus_formatted', [])
        );

        return success();
    }

    /**
     * @throws \Exception
     */
    public function update(GoodsRequest $request, Goods $goods): JsonResponse
    {
        GoodsService::update(
            $goods,
            $this->getInputs(),
            request('tags', []),
            request('cat_ids', []),
            request('skus_formatted', [])
        );

        return success();
    }

    public function delete(Goods $goods)
    {
    }

    private function getInputs(): array
    {
        return inputs([
            'code', 'title', 'usp', 'album', 'brief', 'detail', 'stocks',
            'price', 'packing_fee', 'is_on_sale', 'asc_num',
        ]);
    }
}
