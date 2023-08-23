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

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Sigua\Goods\Models\Category;
use Sigua\Goods\Models\SkuName;
use Sigua\Goods\Models\SkuValue;
use Sigua\Goods\Models\Tag;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tag::upsert([
            ['title' => '新品'],
            ['title' => '热卖'],
            ['title' => '促销'],
            ['title' => '推荐'],
        ], 'id');

        Category::upsert([
            ['id' => 1, 'parent_id' => null, 'title' => '粉面'],
            ['id' => 2, 'parent_id' => null, 'title' => '小吃'],
            ['id' => 3, 'parent_id' => null, 'title' => '快餐'],
            ['id' => 11, 'parent_id' => 1, 'title' => '米粉'],
            ['id' => 12, 'parent_id' => 1, 'title' => '面食'],
            ['id' => 23, 'parent_id' => 2, 'title' => '饼干'],
            ['id' => 24, 'parent_id' => 2, 'title' => '糕点'],
            ['id' => 25, 'parent_id' => 2, 'title' => '其他'],
            ['id' => 31, 'parent_id' => 3, 'title' => '现炒'],
            ['id' => 32, 'parent_id' => 3, 'title' => '熟食'],
            ['id' => 33, 'parent_id' => 3, 'title' => '其它'],
        ], 'id');

        SkuName::upsert([
            ['id' => 1, 'title' => '颜色'],
            ['id' => 2, 'title' => '尺码'],
            ['id' => 3, 'title' => '款式'],
        ], 'id');

        SkuValue::upsert([
            ['name_id' => 1, 'title' => '红'],
            ['name_id' => 1, 'title' => '绿'],
            ['name_id' => 1, 'title' => '蓝'],
            ['name_id' => 2, 'title' => 'S'],
            ['name_id' => 2, 'title' => 'M'],
            ['name_id' => 2, 'title' => 'L'],
            ['name_id' => 2, 'title' => 'XL'],
            ['name_id' => 3, 'title' => '春秋款'],
            ['name_id' => 3, 'title' => '凉夏款'],
            ['name_id' => 3, 'title' => '暖冬款'],
        ], 'id');
    }
}
