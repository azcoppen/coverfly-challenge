<?php

namespace Coverfly\Policies;

use Coverfly\Models\User;
use Coverfly\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
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

    public function comment (User $user, Project $project)
    {
        return true;
    }
}
