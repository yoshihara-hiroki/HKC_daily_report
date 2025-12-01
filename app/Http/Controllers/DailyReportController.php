<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\User;
use App\Http\Requests\StoreDailyReportRequest;
use App\Http\Requests\UpdateDailyReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DailyReportController extends Controller
{
    /**
     * 日報のリストを表示
     */
    public function index(Request $request)
    {
        // 認可チェック（Policyを呼び出す）
        Gate::authorize('viewAny', DailyReport::class);

        // 初期値設定
        $selectedDate = $request->input('report_date', now()->format('Y-m-d'));
        $selectedUserId = $request->input('user_id', $request->user()->id);

        // 指定日・指定ユーザーの日報を取得
        $dailyReport = DailyReport::with('user', 'comments.user')
            ->where('user_id', $selectedUserId)
            ->where('report_date', $selectedDate)
            ->first();

        $users = User::orderBy('name')->get();

        return view('daily-reports.index', compact('dailyReport', 'users', 'selectedDate', 'selectedUserId'));
    }

    /**
     * 日報の新規作成フォーム表示
     */
    public function create(Request $request)
    {
        // 認可チェック（Policyを呼び出す）
        Gate::authorize('create', DailyReport::class);

        // パラメータから日付を取得（デフォルトは今日）
        $defaultDate = $request->input('date', now()->format('Y-m-d'));

        return view('daily-reports.create', compact('defaultDate'));
    }

    /**
     * 新規作成した日報を保存
     */
    public function store(StoreDailyReportRequest $request)
    {
        Gate::authorize('create', DailyReport::class);

        $validated = $request->validated();
        $dailyReport = $request->user()->dailyReports()->create($request->validated());

        return redirect()->route('daily-reports.index', [
            'report_date' => $validated['report_date'],
            'user_id' => $dailyReport->user_id
        ])->with('success', '日報を作成しました。');
    }

    /**
     * 指定した日報を表示
     */
    public function show(DailyReport $dailyReport)
    {
        return redirect()->route('daily-reports.index', [
            'report_date' => $dailyReport->report_date,
            'user_id'=> $dailyReport->user_id
        ]);
    }

    /**
     * 指定した日報の編集フォームを表示
     */
    public function edit(DailyReport $dailyReport)
    {
        Gate::authorize('update', $dailyReport);

        return view('daily-reports.edit', compact('dailyReport'));
    }

    /**
     * 指定した日報の更新を保存
     */
    public function update(UpdateDailyReportRequest $request, DailyReport $dailyReport)
    {
        Gate::authorize('update', $dailyReport);

        $validated = $request->validated();
        $dailyReport->update($request->validated());

        return redirect()->route('daily-reports.index', [
            'report_date' => $validated['report_date'],
            'user_id' => $dailyReport->user_id
        ])->with('success', '日報を更新しました。');
    }

    /**
     * 指定した日報の削除
     */
    public function destroy(DailyReport $dailyReport)
    {
        Gate::authorize('delete', $dailyReport);

        $reportDate = $dailyReport->report_date;
        $userId = $dailyReport->user_id;

        $dailyReport->delete();

        return redirect()->route('daily-reports.index', [
            'report_date' => $reportDate,
            'user_id' => $userId
        ])->with('success', '日報を削除しました。');
    }
}
