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

namespace Sigua\Models\traits;

use Illuminate\Support\Carbon;

trait SerializeDate
{
    protected function serializeDate(\DateTimeInterface $date): string
    {
        return Carbon::instance($date)->toDateTimeString();
    }
}
