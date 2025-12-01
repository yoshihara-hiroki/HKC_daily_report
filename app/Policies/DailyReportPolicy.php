<?php

namespace App\Policies;

use App\Models\DailyReport;
use App\Models\User;

class DailyReportPolicy
{
    /**
     * ユーザーが日報を表示できるかどうかを判断
     */
    public function viewAny(User $user): bool
    {
        return true; // 全ユーザーが閲覧可能
    }

    /**
     * ユーザーが日報を表示できるかどうかを判断
     */
    public function view(User $user, DailyReport $dailyReport): bool
    {
        return true; // 全ユーザーが閲覧可能
    }

    /**
     * ユーザーが日報を作成できるかどうかを判断
     */
    public function create(User $user): bool
    {
        return true; // 全ユーザーが作成可能
    }

    /**
     * ユーザーが日報を更新できるかどうかを判断
     */
    public function update(User $user, DailyReport $dailyReport): bool
    {
        \Log::info('Policy update check', [
            'user_id' => $user->id,
            'report_user_id' => $dailyReport->user_id,
            'result' => $user->id === $dailyReport->user_id
        ]);
        
        return $user->id === $dailyReport->user_id; // 自分の日報のみ編集可能
    }

    /**
     * ユーザーが日報を削除できるかどうかを判断
     */
    public function delete(User $user, DailyReport $dailyReport): bool
    {
        return $user->id === $dailyReport->user_id; // 自分の日報のみ削除可能
    }
}
