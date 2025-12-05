<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWebMeetingRequest;
use App\Http\Requests\UpdateWebMeetingRequest;
use App\Models\WebMeeting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class WebMeetingController extends Controller
{
    /**
     * Web会議予定一覧表示
     */
    public function index(Request $request)
    {
        // 検索条件の取得
        $date = $request->input('date');
        $userId = $request->input('user_id');

        // クエリ
        $query = WebMeeting::with('user')->orderBy('meeting_date', 'desc')->orderBy('start_time', 'asc');

        if ($date) {
            $query->where('meeting_date', $date);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        // 全件取得
        $webMeetings = $query->get();

        // 検索用ユーザーリスト
        $users = User::orderBy('name')->get();

        return view('web-meetings.index', compact('webMeetings', 'users', 'date', 'userId'));
    }

    /**
     * 新規作成画面
     */
    public function create(Request $request)
    {
        // URLパラメータから日付を取得（カレンダー等からの遷移用）
        $defaultDate = $request->input('date', now()->format('Y-m-d'));

        return view('web-meetings.create', compact('defaultDate'));
    }

    /**
     * 新規登録処理
     */
    public function store(StoreWebMeetingRequest $request)
    {
        $validated = $request->validated();

        $request->user()->webMeetings()->create($validated);

        $date = Carbon::parse($validated['meeting_date'])->format('Y-m-d');

        return redirect()->route('web-meetings.index', ['date' => $date])
            ->with('success', 'Web会議予定を登録しました。');
    }

    /**
     * 編集画面
     */
    public function edit(WebMeeting $webMeeting)
    {
        Gate::authorize('update', $webMeeting);
        return view('web-meetings.edit', compact('webMeeting'));
    }

    /**
     * 更新処理
     */
    public function update(UpdateWebMeetingRequest $request, WebMeeting $webMeeting)
    {
        Gate::authorize('update', $webMeeting);

        $validated = $request->validated();

        $webMeeting->update($validated);

        $date = Carbon::parse($validated['meeting_date'])->format('Y-m-d');

        return redirect()->route('web-meetings.index', ['date' => $date])
            ->with('success', 'Web会議予定を更新しました。');
    }

    /**
     * 削除処理
     */
    public function destroy(WebMeeting $webMeeting)
    {
        Gate::authorize('delete', $webMeeting);

        // 削除前に日付を保持
        $date = Carbon::parse($webMeeting->meeting_date)->format('Y-m-d');

        $webMeeting->delete();

        return redirect()->route('web-meetings.index', ['date' => $date])
            ->with('success', 'Web会議予定を削除しました。');
    }
}
