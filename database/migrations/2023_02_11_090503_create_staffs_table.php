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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('staff_departments', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->unsignedInteger('parent_id')->nullable()->comment('上级部门 id');
            $table->string('title')->comment('部门名称');
            $table->string('brief')->default('')->comment('部门简介');
            $table->unsignedMediumInteger('asc_num')->default(9);
            $table->unsignedInteger('staff_count')->default(0)->comment('成员数量（包括子部门）');
            $table->timestamps();
        });

        Schema::create('staffs', static function (Blueprint $table) {
            $table->id()->from(10000);
            $table->unsignedInteger('department_id')->nullable();
            $table->string('avatar')->default('')->comment('头像URL');
            $table->string('name')->default('')->index();
            $table->string('mobile')->default('')->index();
            $table->string('email')->default('')->index();
            $table->string('password')->default('');
            $table->string('realname')->default('')->comment('真实姓名');
            $table->unsignedTinyInteger('enabled')->default(1)->comment('是否启用/禁用');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('department_id')->references('id')->on('staff_departments');
            $table->index('remember_token');
        });

        (new \Sigua\Admin\Models\Staff())->forceFill([
            'id' => 1,
            'name' => 'admin',
            'realname' => 'Administrator',
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(60),
        ])->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_departments');
        Schema::dropIfExists('staffs');
    }
};
