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

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 商品使用规格名的中间表。
 */
class SkuNameUse extends Pivot
{
    protected $table = 'goods_sku_name_uses';

    protected $fillable = [
        'goods_id',
        'name_id',
        'enable_image',
        'show_image',
        'sort_index',
    ];

    protected $casts = [
        'goods_id' => 'integer',
        'name_id' => 'integer',
        'enable_image' => 'boolean',
        'show_image' => 'boolean',
    ];
}
