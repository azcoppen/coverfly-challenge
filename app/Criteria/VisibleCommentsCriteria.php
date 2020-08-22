<?php

namespace Coverfly\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

use Coverfly\Models\User;

/**
 * Class VisibleCommentsCriteriaCriteria.
 *
 * @package namespace Coverfly\Criteria;
 */
class VisibleCommentsCriteria implements CriteriaInterface
{
    public $user;

    public function __construct ( User $user )
    {
        $this->user = $user;
    }

    private function users_groups_members ()
    {
        $data = collect ([$this->user->id]);
        $siblings = collect ([]);
        foreach ($this->user->memberships AS $membership)
        {
            if ( $membership->role == 'owner' )
            {
                $siblings->push ( $membership->group->memberships()->withTrashed()->get()->pluck ('member_id')->all() );
            }
            else
            {
                $siblings->push ( $membership->group->memberships->pluck ('member_id')->all() );
            }
        }

        return $data->merge ($siblings->flatten())->unique()->all();
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if ( $this->user->memberships->count() < 1 )
        {
            return $model->with ([
                'comments' => function ($query) {
                    return $query
                    ->where ('commented_type', get_class ($this->user))
                    ->where ('commented_id', $this->user->id)
                    ->orderBy ('created_at', 'DESC');
                },
                'comments.commented.memberships.group',
            ]);
        }
        else
        {
            return $model->with ([
                'comments' => function ($query) {
                    return $query
                    ->where ('commented_type', get_class ($this->user))
                    ->whereIn ('commented_id', $this->users_groups_members ())
                    ->orderBy ('created_at', 'DESC');
                },
                'comments.commented.memberships.group',
            ]);
        }


        return $model;
    }
}
