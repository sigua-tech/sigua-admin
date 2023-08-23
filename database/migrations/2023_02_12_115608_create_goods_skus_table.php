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
        Schema::create('goods_sku_names', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('goods_sku_values', function (Blueprint $table) {
            $table->id()->from(100000);
            $table->unsignedBigInteger('name_id');
            $table->string('title');
            $table->timestamps();
            $table->foreign('name_id')->references('id')->on('goods_sku_names');
        });

        Schema::create('goods_sku_name_uses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_id');
            $table->unsignedBigInteger('name_id');
            $table->unsignedTinyInteger('enable_image')->default(0)->comment('规格启用图片');
            $table->unsignedTinyInteger('show_image')->default(0)->comment('商品详情规格列表显示为图片形式');
            $table->unsignedTinyInteger('sort_index')->comment('显示顺序序号（自动）');
            $table->timestamps();
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('name_id')->references('id')->on('goods_sku_names');
        });

        Schema::create('goods_sku_value_uses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_id');
            $table->unsignedBigInteger('value_id');
            $table->string('image_path')->default('')->comment('规格图片');
            $table->timestamps();
            $table->foreign('goods_id')->references('id')->on('goods');
            $table->foreign('value_id')->references('id')->on('goods_sku_values');
        });

        Schema::create('goods_skus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('goods_id');
            $table->string('sku_key')->comment('规格值列表（逻辑关联 goods_sku_values），从小到大排序，以半角逗号隔开');
            $table->string('code')->default('');
            $table->decimal('price');
            $table->integer('stocks')->default(0)->comment('可用库存');
            $table->unsignedBigInteger('sale_count')->default(0)->comment('总销量');
            $table->timestamps();
            $table->unique(['goods_id', 'sku_key']);
            $table->foreign('goods_id')->references('id')->on('goods');
        });
    }

    public function down()
    {
        Schema::dropIfExists('goods_sku_names');
        Schema::dropIfExists('goods_sku_values');
        Schema::dropIfExists('goods_sku_name_uses');
        Schema::dropIfExists('goods_sku_value_uses');
        Schema::dropIfExists('goods_skus');
    }
};
