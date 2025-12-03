<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\DailyReport;
use App\Models\DailyReportComment;
use App\Models\Schedule;
use App\Models\User;
use App\Policies\DailyReportPolicy;
use App\Policies\DailyReportCommentPolicy;
use App\Policies\SchedulePolicy;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ポリシーの登録
        Gate::policy(DailyReport::class, DailyReportPolicy::class);
        Gate::policy(DailyReportComment::class, DailyReportCommentPolicy::class);
        Gate::policy(Schedule::class, SchedulePolicy::class);

        // 管理者ゲートの定義（roleがadminのみ管理者権限）
        Gate::define('admin', function (User $user) {
            return $user->role === 'admin';
        });
    }
}