<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\Schedule;
use App\Models\Group;
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
        // 表示する年月
        $currentDate = $request->input('date')
            ? Carbon::parse($request->input('date'))
            : Carbon::now();

        // 選択されたグループID
        $selectedGroupId = $request->input('group_id');

        // カレンダー範囲
        $startDate = $currentDate->copy()->startOfMonth()->startOfWeek(CarbonInterface::SUNDAY);
        $endDate = $currentDate->copy()->endOfMonth()->endOfWeek(CarbonInterface::SUNDAY);

        // クエリの構築
        $query = Schedule::with('user');

        // グループ絞り込み
        if ($selectedGroupId) {
            $query->whereHas('user.groups', function ($q) use ($selectedGroupId) {
                $q->where('groups.id', $selectedGroupId);
            });
        }

        // 期間とソート
        $schedules = $query->whereBetween('schedule_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->orderBy('start_time')
            ->get();

        // Alpine.js（カレンダー）で扱いやすいようにデータを整形・変換する
        $events = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'user_id' => $schedule->user_id,
                'title' => $schedule->destination,
                'start' => Carbon::parse($schedule->schedule_date)->format('Y-m-d'),

                // datetimeキャスト対策：時間だけを文字列(H:i)で抽出
                'time' => $schedule->start_time ? $schedule->start_time->format('H:i') : '',
                'end_time' => $schedule->end_time ? $schedule->end_time->format('H:i') : null,

                'user_name' => $schedule->user->name,

                // Web会議情報もJSに渡す
                'is_web_meeting' => $schedule->is_web_meeting,
                'meeting_type' => $schedule->meeting_type,
            ];
        });

        // グループ一覧を取得
        $groups = Group::all();

        return view('schedules.calendar', compact(
            'currentDate',
            'startDate',
            'endDate',
            'events',          
            'groups',
            'selectedGroupId'
        ));
    }
}
