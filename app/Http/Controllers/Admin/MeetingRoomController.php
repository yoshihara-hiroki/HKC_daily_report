<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use Illuminate\Http\Request;

class MeetingRoomController extends Controller
{
    /**
     * 会議室一覧表示
     */
    public function index()
    {
        $meetingRooms = MeetingRoom::orderBy('id', 'asc')->get();
        return view('admin.meeting_rooms.index', compact('meetingRooms'));
    }

    /**
     * 新規作成フォーム
     */
    public function create()
    {
        return view('admin.meeting_rooms.create');
    }

    /**
     * 保存処理
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:meeting_rooms,name',
            'is_active' => 'boolean',
        ]);

        MeetingRoom::create([
            'name' => $request->name,
            'is_active' => $request->has('is_active'), // チェックボックス対応
        ]);

        return redirect()->route('admin.meeting-rooms.index')
            ->with('success', '会議室を登録しました。');
    }

    /**
     * 編集フォーム
     */
    public function edit(MeetingRoom $meetingRoom)
    {
        return view('admin.meeting_rooms.edit', compact('meetingRoom'));
    }

    /**
     * 更新処理
     */
    public function update(Request $request, MeetingRoom $meetingRoom)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:meeting_rooms,name,' . $meetingRoom->id,
            'is_active' => 'boolean',
        ]);

        $meetingRoom->update([
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.meeting-rooms.index')
            ->with('success', '会議室情報を更新しました。');
    }

    /**
     * 削除処理
     */
    public function destroy(MeetingRoom $meetingRoom)
    {
        // 予約が入っている場合の制御などは必要に応じて追加（今回は強制削除）
        $meetingRoom->delete();

        return redirect()->route('admin.meeting-rooms.index')
            ->with('success', '会議室を削除しました。');
    }
}
