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
    public function up(): void
    {
        Schema::create('staff_roles', static function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('角色名称');
            $table->string('brief')->default('')->comment('角色简介');
            $table->unsignedMediumInteger('asc_num')->default(9);
            $table->json('permissions')->nullable();
            $table->unsignedInteger('staff_count')->default(0)->comment('成员数量');
            $table->unsignedTinyInteger('enabled')->default(1);
            $table->timestamps();
        });
        Schema::create('staff_role_pivots', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('role_id');
            $table->timestamps();
            $table->foreign('staff_id')->references('id')->on('staffs');
            $table->foreign('role_id')->references('id')->on('staff_roles');
        });

        \Sigua\Admin\Models\StaffRole::upsert([
            ['title' => '系统管理员', 'brief' => '拥有系统全部权限', 'permissions' => '["*"]'],
            ['title' => '编辑', 'brief' => '', 'permissions' => '[]'],
        ], 'id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_roles');
        Schema::dropIfExists('staff_role_pivots');
    }
};
