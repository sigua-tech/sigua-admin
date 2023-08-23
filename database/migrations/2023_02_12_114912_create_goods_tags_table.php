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
        Schema::create('goods_tags', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->unsignedInteger('goods_count')->default(0)->comment('引用商品数');
        });

        Schema::create('goods_tag_pivots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_id');
            $table->unsignedBigInteger('tag_id');
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('tag_id')->references('id')->on('goods_tags');
            $table->unique(['goods_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('goods_tags');
        Schema::dropIfExists('goods_tag_pivots');
    }
};
