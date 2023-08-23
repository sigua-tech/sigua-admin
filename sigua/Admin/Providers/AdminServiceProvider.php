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

namespace Sigua\Admin\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Sigua\Admin\Models\Staff;
use Sigua\Admin\Services\StaffPermissionService;

class AdminServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerAdminGuard();
        $this->mergeConfigFrom(config_path('permissions.php'), 'permissions');
        $this->loadRoutesFrom(base_path('routes/admin.php'));
        $this->loadViewsFrom(resource_path('admin/views'), 'admin');
    }

    private function registerAdminGuard()
    {
        Config::set('auth.guards.admin', [
            'driver' => 'session',
            'provider' => 'staffs',
        ]);

        Config::set('auth.providers.staffs', [
            'driver' => 'eloquent',
            'model' => Staff::class,
        ]);

        // 管理后台 rbac
        Gate::before(function (Staff $staff, string $ability) {
            return StaffPermissionService::can($staff, $ability);
        });
    }
}
