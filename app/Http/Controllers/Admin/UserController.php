<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * ユーザー一覧表示
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::with('groups')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    /**
     * 新規登録画面
     */
    public function create()
    {
        // 部署選択用の全グループ取得
        $groups = Group::orderBy('id')->get();

        return view('admin.users.create', compact('groups'));
    }

    /**
     * 新規登録処理
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // ユーザー作成
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // 部署（グループ）の紐付け
        if (isset($validated['group_ids'])) {
            $user->groups()->sync($validated['group_ids']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'ユーザーを登録しました。');
    }

    /**
     * 編集フォーム
     */
    public function edit(User $user)
    {
        $groups = Group::orderBy('id')->get();

        // 現在の所属部署IDの配列を取得（チェックボックスの初期値）
        $assignedGroupIds = $user->groups->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'groups', 'assignedGroupIds'));
    }

    /**
     * 更新処理
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        // 基本情報の更新
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        // パスワードが入力されている場合のみ更新
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // 部署（グループ）の紐付け更新
        // チェックがない場合は空配列を渡して全解除
        $user->groups()->sync($validated['group_ids'] ?? []);
    }

    /**
     * 削除処理
     */
    public function destroy(User $user)
    {
        // 自分自身は削除できないようにする安全策
        if ($user->id === auth()->id()) {
            return back()->with('error', '自分自身のアカウントは削除できません。');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'ユーザーを削除しました。');
    }
}
