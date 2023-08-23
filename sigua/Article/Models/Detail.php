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

namespace Sigua\Article\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sigua\Models\Model;

/**
 * @property string $detail
 */
class Detail extends Model
{
    protected $table = 'article_details';

    protected $fillable = ['id', 'detail'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'id', 'id', 'id');
    }
}
