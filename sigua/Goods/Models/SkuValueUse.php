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

namespace Sigua\Goods\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 商品使用规格值的中间表。
 * @property Carbon $created_at
 */
class SkuValueUse extends Pivot
{
    protected $table = 'goods_sku_value_uses';

    protected $fillable = [
        'goods_id',
        'value_id',
        'image_path',
    ];

    protected $casts = [
        'goods_id' => 'integer',
        'value_id' => 'integer',
    ];
}
