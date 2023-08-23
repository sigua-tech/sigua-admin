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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Sigua\Models\Store;

class StoreSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Store::upsert([
            ['id' => 1, 'title' => '阳光路店', 'address' => '阳光路100号', 'info' => '', 'album' => '[]'],
            ['id' => 2, 'title' => '武汉路店', 'address' => '武汉路1180号', 'info' => '', 'album' => '[]'],
            ['id' => 3, 'title' => '长春路店', 'address' => '长春路120号', 'info' => '', 'album' => '[]'],
        ], 'id');
    }
}
