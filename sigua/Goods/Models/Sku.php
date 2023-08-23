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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sigua\Models\Model;

/**
 * @property int $id
 * @property int $goods_id
 * @property string $sku_key
 * @property string $code
 * @property float $price
 * @property int $stocks 库存
 * @property int $sale_count sku 销量
 */
class Sku extends Model
{
    use HasFactory;

    protected $table = 'goods_skus';

    protected $fillable = [
        'goods_id',
        'sku_key',
        'code',
        'price',
        'stocks',
    ];

    protected $casts = [
        'goods_id' => 'integer',
        'price' => 'float',
        'stocks' => 'integer',
        'sale_count' => 'integer',
    ];
}
