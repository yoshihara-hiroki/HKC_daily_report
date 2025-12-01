<?php

use App\Http\Controllers\DailyReportCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DailyReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // デバッグ用
    Route::get('/debug-user', function() {
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
        ->name('daily-reports.commentsupdate');
    Route::delete('daily-reports/{dailyReport}/comments/{comment}', [DailyReportCommentController::class, 'destroy'])
        ->name('daily-reports.comments.destroy');
});

require __DIR__.'/auth.php';