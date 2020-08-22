<?php

namespace Coverfly\Policies;

use Coverfly\Models\Group;
use Coverfly\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function decrement (User $user, Group $group)
    {
        return $user->memberships()->where('group_id', $group->id)->count() > 0 && $user->memberships()->where('group_id', $group->id)->value('role') == 'owner' ? Response::allow() : Response::deny();
    }

    public function increment (User $user, Group $group)
    {
        return $user->memberships()->where('group_id', $group->id)->count() > 0 && $user->memberships()->where('group_id', $group->id)->value('role') == 'owner' ? Response::allow() : Response::deny();
    }
}
