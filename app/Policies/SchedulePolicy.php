<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    /**
     * 誰でも一覧を確認可能
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * 誰でも詳細を確認可能
     */
    public function view(User $user, Schedule $schedule): bool
    {
        return true;
    }

    /**
     * 誰でも作成可能
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * 自分の予定のみ編集可能
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return $user->id === $schedule->user_id;
    }

    /**
     * 自分の予定のみ削除可能
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        return $user->id === $schedule->user_id;
    }

}
