<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyReportComment;
use App\Http\Requests\StoreDailyReportCommentRequest;
use App\Http\Requests\UpdateDailyReportCommentRequest;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class DailyReportCommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(StoreDailyReportCommentRequest $request, DailyReport $dailyReport)
    {
        Gate::authorize('create', DailyReportComment::class);

        // 既にコメントが存在する場合はエラー
        $existingComment = $dailyReport->comments()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($existingComment) {
            return redirect()->route('daily-reports.index', [
                'report_date' => Carbon::parse($dailyReport->report_date)->format('Y-m-d'),
                'user_id' => $dailyReport->user_id
            ])->withErrors(['comment' => '既にこの日報にコメントを投稿しています。編集してください。']);
        }

        $dailyReport->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $request->validated()['comment'],
        ]);

        return redirect()->route('daily-reports.index', [
            'report_date' => Carbon::parse($dailyReport->report_date)->format('Y-m-d'),
            'user_id' => $dailyReport->user_id
        ])->with('success', 'コメントを投稿しました。');
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(UpdateDailyReportCommentRequest $request, DailyReport $dailyReport, DailyReportComment $comment)
    {
        Gate::authorize('update', $comment);

        $comment->update($request->validated());

        return redirect()->route('daily-reports.index', [
            'report_date' => Carbon::parse($dailyReport->report_date)->format('Y-m-d'),
            'user_id' => $dailyReport->user_id
        ])->with('success', 'コメントを更新しました。');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(DailyReport $dailyReport, DailyReportComment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect()->route('daily-reports.index', [
            'report_date' => Carbon::parse($dailyReport->report_date)->format('Y-m-d'),
            'user_id' => $dailyReport->user_id
        ])->with('success', 'コメントを削除しました。');
    }
}