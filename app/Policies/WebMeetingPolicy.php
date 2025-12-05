<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebMeeting;

class WebMeetingPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, WebMeeting $webMeeting): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, WebMeeting $webMeeting): bool
    {
        return $user->id === $webMeeting->user_id;
    }

    public function delete(User $user, WebMeeting $webMeeting): bool
    {
        return $user->id === $webMeeting->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WebMeeting $webMeeting): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WebMeeting $webMeeting): bool
    {
        return false;
    }
}
