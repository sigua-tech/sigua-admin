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

namespace Sigua\Models;

class Image extends Model
{
    protected $fillable = ['path', 'hash', 'type', 'user_id', 'staff_id', 'size', 'module'];

    protected $casts = [
        'staff_id' => 'integer',
        'user_id' => 'integer',
        'size' => 'integer',
    ];
}
