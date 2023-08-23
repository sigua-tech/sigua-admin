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

class Setting extends Model
{
    public $timestamps = false;

    protected $table = 'settings';

    protected $fillable = ['key', 'group', 'title', 'value', 'input_type', 'options', 'tips'];

    protected $casts = [
        'options' => 'array',
    ];
}
