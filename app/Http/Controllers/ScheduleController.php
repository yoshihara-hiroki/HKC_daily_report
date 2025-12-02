<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class ScheduleController extends Controller
{
    /**
     * 行先予定一覧を表示
     */
    public function index()
    {
        // 自分の予定を取得（新しい順）
        $schedules = auth()->user()->schedules()
            ->orderBy('schedule_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(20);

        return view('schedules.index', compact('schedules'));
    }

    /**
     * 新規作成フォームを表示
     */
    public function create(Request $request)
    {
        // パラメータから日付を取得（デフォは今日）
        $defaultDate = $request->input('date', date('Y-m-d'));
        return view('schedules.create', compact('defaultDate'));
    }

    /**
     * 新規登録処理
     */
    public function store(StoreScheduleRequest $request)
    {
        $request->user()->schedules()->create($request->validated());

        return redirect()->route('schedules.index')
            ->with('success', '行先予定を登録しました。');
    }

    /**
     * 編集フォームを表示
     */
    public function edit(Schedule $schedule)
    {
        Gate::authorize('update', $schedule);

        return view('schedules.edit', compact('schedule'));
    }

    /**
     * 更新処理
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        Gate::authorize('update', $schedule);

        $schedule->update($request->validated());

        return redirect()->route('schedules.index')
            ->with('success', '行先予定を更新しました。');
    }

    /**
     * 削除処理
     */
    public function destroy(Schedule $schedule)
    {
        Gate::authorize('delete', $schedule);

        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', '行先予定を削除しました。');
    }

    /**
     * 全社員の予定をカレンダー表示
     */
    public function calendar(Request $request)
    {
        // 表示する年月を取得（指定がなければ今月）
        $currentDate = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::now();

        // カレンダーの開始日と終了日を計算（月初の週の日曜 ～ 月末の週の土曜）
        // startOfWeek(Carbon::SUNDAY) で日曜始まりに設定
        $startDate = $currentDate->copy()->startOfMonth()->startOfWeek(CarbonInterface::SUNDAY);
        $endDate = $currentDate->copy()->endOfMonth()->endOfWeek(CarbonInterface::SUNDAY);

        // 期間内の全スケジュールを取得（ユーザー情報も一緒に）
        $schedules = Schedule::with('user')
            ->whereBetween('schedule_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('start_time')
            ->get();

        // 日付ごとにスケジュールをグループ化（Viewで使いやすくするため）
        // キー: '2025-11-01', 値: その日のスケジュールコレクション
        $schedulesByDate = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->schedule_date)->format('Y-m-d');
        });

        return view('schedules.calendar', compact('currentDate', 'startDate', 'endDate', 'schedulesByDate'));
    }
}
