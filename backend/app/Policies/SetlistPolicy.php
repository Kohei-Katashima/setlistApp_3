<?php

namespace App\Policies;

use App\User;
use App\Models\Setlist;
use Illuminate\Auth\Access\HandlesAuthorization;

class SetlistPolicy
{
    use HandlesAuthorization;

    /**
     * フォルダの閲覧権限
     * @param User $user
     * @param Setlist $setlist
     * @return bool
     */
    public function view(User $user, Setlist $setlist)
    {
        return $user->id === $setlist->user_id;
    }
}
