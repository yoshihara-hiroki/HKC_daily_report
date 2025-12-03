<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
    /**
     * グループ一覧表示
     */
    public function index()
    {
        Gate::authorize('admin'); // 管理者のみ

        $groups = Group::withCount('users')->get(); // 所属人数を取得

        return view('admin.groups.index', compact('groups'));
    }

    /**
     * グループ編集画面
     */
    public function edit(Group $group)
    {
        Gate::authorize('admin');

        // 全社員を取得（選択肢用）
        $users = User::orderBy('name')->get();

        return view('admin.groups.edit', compact('group', 'users'));
    }

    /**
     * グループ更新処理（グルーピング）
     */
    public function update(Request $request, Group $group)
    {
        Gate::authorize('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'users' => ['array'],        // 選択されたユーザーIDの配列
            'users.*' => ['integer', 'exists:users,id'],
        ]);

        // グループ情報の更新
        $group->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // 所属社員の同期処理（チェックされたユーザーだけ所属に更新）
        $group->users()->sync($validated['users'] ?? []);

        return redirect()->route('admin.groups.index')
            ->with('success', 'グループ情報を更新しました。');
    }

}
