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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('creator_id');
            $table->string('author')->default('')->comment('作者');
            $table->string('source')->default('')->comment('来源');
            $table->string('editor')->default('')->comment('编辑');
            $table->string('brief')->default('')->comment('简介');
            $table->json('album')->nullable()->comment('商品相册');
            $table->unsignedSmallInteger('asc_num')->default(50)->comment('正序排序数');
            $table->unsignedSmallInteger('desc_num')->comment('asc_num 反向数，用于反向排序，9999 - asc_num');
            $table->enum('status', ['draft', 'published'])->comment('状态：draft）草稿，published）已发布');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('creator_id')->references('id')->on('staffs');
            $table->index(['asc_num', 'id']);
            $table->index(['desc_num', 'id']);
        });
        Schema::create('article_details', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->mediumText('detail')->nullable()->comment('详细介绍');
            $table->foreign('id')->references('id')->on('articles');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
        Schema::dropIfExists('article_details');
    }
};
