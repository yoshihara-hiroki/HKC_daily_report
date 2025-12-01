<?php

namespace App\Policies;

use App\Models\DailyReportComment;
use App\Models\User;

class DailyReportCommentPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // 全ユーザーがコメント投稿可能
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DailyReportComment $dailyReportComment): bool
    {
        return $user->id === $dailyReportComment->user_id; // 自分のコメントのみ編集可能
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DailyReportComment $dailyReportComment): bool
    {
        return $user->id === $dailyReportComment->user_id; // 自分のコメントのみ削除可能
    }
}