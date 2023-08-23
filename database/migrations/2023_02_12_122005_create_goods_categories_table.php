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
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('goods_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('title');
            $table->string('brief')->default('')->comment('简介');
            $table->unsignedMediumInteger('goods_count')->default(0);
            $table->unsignedTinyInteger('is_show')->default(1)->comment('在顾客端菜单中是否显示');
            $table->unsignedSmallInteger('asc_num')->default(99)->comment('正序排序数');
            $table->timestamps();
        });
        Schema::create('goods_category_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamps();
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('category_id')->references('id')->on('goods_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('goods_categories');
        Schema::dropIfExists('goods_category_pivots');
    }
};
