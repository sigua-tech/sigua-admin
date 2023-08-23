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

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sigua\Models\Model;

/**
 * @property mixed $id
 */
class Tag extends Model
{
    protected $table = 'article_tags';

    protected $fillable = ['title'];

    protected $casts = [
        'article_count' => 'integer',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tag_pivots', 'tag_id', 'article_id');
    }
}
