<?php
declare(strict_types=1);

use Sigua\Admin\Controllers\AccountController;
use Sigua\Admin\Controllers\AuthController;
use Sigua\Admin\Controllers\HomeController;
use Sigua\Admin\Controllers\ImageController;
use Sigua\Admin\Controllers\SettingController;
use Sigua\Admin\Controllers\Staff\DepartmentController;
use Sigua\Admin\Controllers\Staff\RoleController;
use Sigua\Admin\Controllers\StaffController;
use Sigua\Article\Controllers\Admin\ArticleController;
use Sigua\Goods\Controllers\Admin\CategoryController;
use Sigua\Goods\Controllers\Admin\GoodsController;
use Sigua\Goods\Controllers\Admin\SkuNameController;
use Sigua\Goods\Controllers\Admin\SkuValueController;
use Sigua\Goods\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin.home');
    Route::get('/auth/profile', [AuthController::class, 'profile'])->name('admin.profile');
    Route::post('/auth/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // 登录后可访问
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard');

        // 文章分类
        Route::controller(Sigua\Article\Controllers\Admin\CategoryController::class)->group(function () {
            Route::get('/articles/categories', 'index')->middleware('can:article.category.viewAll');
            Route::post('/articles/categories', 'create')->middleware('can:article.category.create');
            Route::get('/articles/categories/{category}', 'show')->middleware('can:article.category.view');
            Route::put('/articles/categories/{category}', 'update')->middleware('can:article.category.update');
            Route::delete('/articles/categories/{category}', 'destroy')->middleware('can:article.category.delete');
        });

        // 文章标签
        Route::controller(Sigua\Article\Controllers\Admin\TagController::class)->group(function () {
            Route::get('/articles/tags/hot', 'hot');
            Route::get('/articles/tags', 'index')->middleware('can:article.tag.viewAll');
            Route::post('/articles/tags', 'create')->middleware('can:article.tag.create');
            Route::get('/articles/tags/{tag}', 'show')->middleware('can:article.tag.view');
            Route::put('/articles/tags/{tag}', 'update')->middleware('can:article.tag.update');
            Route::delete('/articles/tags/bat/delete', 'batDelete')->middleware('can:article.tag.batDelete');
            Route::delete('/articles/tags/{tag}', 'destroy')->middleware('can:article.tag.delete');
        });

        // 文章
        Route::controller(ArticleController::class)->group(function () {
            Route::get('/articles', 'index')->middleware('can:article.viewAll');
            Route::get('/articles/{article}', 'show')->middleware('can:article.view');
            Route::post('/articles', 'create')->middleware('can:article.create');
            Route::put('/articles/{article}', 'update')->middleware('can:article.update');
            Route::delete('/articles/{article}', 'delete')->middleware('can:article.delete');
        });

        // 商品分类
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/goods/categories', 'index')->middleware('can:goods.category.viewAll');
            Route::post('/goods/categories', 'create')->middleware('can:goods.category.create');
            Route::get('/goods/categories/{category}', 'show')->middleware('can:goods.category.view');
            Route::put('/goods/categories/{category}', 'update')->middleware('can:goods.category.update');
            Route::delete('/goods/categories/{category}', 'destroy')->middleware('can:goods.category.delete');
        });

        // 商品标签
        Route::controller(TagController::class)->group(function () {
            Route::get('/goods/tags/hot', 'hot');
            Route::get('/goods/tags', 'index')->middleware('can:goods.tag.viewAll');
            Route::post('/goods/tags', 'create')->middleware('can:goods.tag.create');
            Route::get('/goods/tags/{tag}', 'show')->middleware('can:goods.tag.view');
            Route::put('/goods/tags/{tag}', 'update')->middleware('can:goods.tag.update');
            Route::delete('/goods/tags/bat/delete', 'batDelete')->middleware('can:goods.tag.batDelete');
            Route::delete('/goods/tags/{tag}', 'destroy')->middleware('can:goods.tag.delete');
        });

        // 规格
        Route::get('/goods/sku_names', [SkuNameController::class, 'index']);
        Route::get('/goods/sku_values', [SkuValueController::class, 'index']);

        // 商品
        Route::controller(GoodsController::class)->group(function () {
            Route::get('/goods', 'index')->middleware('can:goods.viewAll');
            Route::get('/goods/{goods}', 'show')->middleware('can:goods.view');
            Route::post('/goods', 'create')->middleware('can:goods.create');
            Route::put('/goods/{goods}', 'update')->middleware('can:goods.update');
            Route::delete('/goods/{goods}', 'delete')->middleware('can:goods.delete');
        });

        // 部门
        Route::controller(DepartmentController::class)->group(function () {
            Route::get('/staffs/departments', 'index')->middleware('can:staff.department.viewAll');
            Route::post('/staffs/departments', 'create')->middleware('can:staff.department.create');
            Route::get('/staffs/departments/{department}', 'show')->middleware('can:staff.department.view');
            Route::put('/staffs/departments/{department}', 'update')->middleware('can:staff.department.update');
            Route::delete('/staffs/departments/{department}', 'destroy')->middleware('can:staff.department.delete');
        });

        // 角色
        Route::controller(RoleController::class)->group(function () {
            Route::get('/staffs/roles', 'index')->middleware('can:staff.role.viewAll');
            Route::post('/staffs/roles', 'create')->middleware('can:staff.role.create');
            Route::get('/staffs/roles/{role}', 'show')->middleware('can:staff.role.view');
            Route::put('/staffs/roles/{role}', 'update')->middleware('can:staff.role.update');
            Route::delete('/staffs/roles/{role}', 'destroy')->middleware('can:staff.role.delete');
        });

        // 员工
        Route::controller(StaffController::class)->group(function () {
            Route::get('/staffs', 'index')->middleware('can:staff.viewAll');
            Route::post('/staffs', 'create')->middleware('can:staff.create');
            Route::get('/staffs/{staff}', 'show')->withTrashed()->middleware('can:staff.view');
            Route::put('/staffs/{staff}', 'update')->middleware('can:staff.update');
            Route::delete('/staffs/bat/delete', 'batDelete')->middleware('can:staff.batDelete');
            Route::delete('/staffs/{staff}', 'destroy')->middleware('can:staff.delete');
        });
        // 账号
        Route::controller(AccountController::class)->group(function () {
            Route::patch('/account/chpw', 'chpw');
            Route::patch('/account/ch_profile', 'chProfile');
        });
        // 系统设置
        Route::controller(SettingController::class)->group(function () {
            Route::get('/settings', 'index');
            Route::post('/settings', 'save');
        });
        // 图片上传
        Route::post('/images', [ImageController::class, 'save']);
    });
});
