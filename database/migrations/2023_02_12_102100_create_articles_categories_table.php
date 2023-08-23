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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up()
    {
        Schema::create('article_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title');
            $table->string('brief')->default('')->comment('简介');
            $table->unsignedMediumInteger('article_count')->default(0);
            $table->unsignedTinyInteger('is_show')->default(1)->comment('在前台菜单中是否显示');
            $table->unsignedSmallInteger('asc_num')->default(99)->comment('正序排序数');
            $table->timestamps();
        });
        Schema::create('article_category_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('category_id')->references('id')->on('article_categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('article_categories');
        Schema::dropIfExists('article_category_pivots');
    }
};
