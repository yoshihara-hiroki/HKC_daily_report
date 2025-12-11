<?php

use App\Http\Controllers\DailyReportCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\WebMeetingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MeetingRoomController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('daily-reports.index') : view('auth.login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // デバッグ用
    Route::get('/debug-user', function () {
        return [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email,
        ];
    });

    // 日報機能
    Route::resource('daily-reports', DailyReportController::class);

    // 日報コメント
    Route::post('daily-reports/{dailyReport}/comments', [DailyReportCommentController::class, 'store'])
        ->name('daily-reports.comments.store');
    Route::put('daily-reports/{dailyReport}/comments/{comment}', [DailyReportCommentController::class, 'update'])
        ->name('daily-reports.comments.update');
    Route::delete('daily-reports/{dailyReport}/comments/{comment}', [DailyReportCommentController::class, 'destroy'])
        ->name('daily-reports.comments.destroy');

    // 行先予定カレンダー表示
    Route::get('schedules/calendar', [ScheduleController::class, 'calendar'])->name('schedules.calendar');

    // 行先予定
    Route::resource('schedules', ScheduleController::class)->except(['show']);

    // 管理者ルート
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {

        // 部署（グループ）管理
        Route::resource('groups', GroupController::class);

        // ユーザー管理
        Route::resource('users', UserController::class); 

        // 会議室管理
        Route::resource('meeting-rooms', MeetingRoomController::class)->except(['show']);
    });
});

require __DIR__ . '/auth.php';