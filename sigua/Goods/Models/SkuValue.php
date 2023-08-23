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

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sigua\Models\Model;

/**
 * @property int $id
 * @property int $name_id
 * @property string $title
 * @property SkuName $name
 */
class SkuValue extends Model
{
    protected $table = 'goods_sku_values';

    protected $fillable = ['name_id', 'title'];

    protected $casts = [
        'name_id' => 'integer',
    ];

    public function name(): BelongsTo
    {
        return $this->belongsTo(SkuName::class, 'name_id');
    }
}
