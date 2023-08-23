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

use Illuminate\Database\Eloquent\Relations\HasMany;
use Sigua\Models\Model;

/**
 * @property int $id
 * @property string $title
 */
class SkuName extends Model
{
    protected $table = 'goods_sku_names';

    protected $fillable = ['title'];

    public function values(): HasMany
    {
        return $this->hasMany(SkuValue::class, 'name_id');
    }
}
