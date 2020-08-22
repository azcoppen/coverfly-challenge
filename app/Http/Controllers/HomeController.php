<?php

namespace Coverfly\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

use Illuminate\Support\Facades\Crypt;

use Coverfly\Contracts\Repositories\UserRepository;
use Coverfly\Contracts\Repositories\ProjectRepository;
use Coverfly\Contracts\Repositories\MembershipRepository;
use Coverfly\Criteria\VisibleCommentsCriteria;

use Coverfly\Http\Requests\StoreProjectComment;

use Coverfly\Models\Project;
use Coverfly\Models\Group;
use Coverfly\Models\User;

use \Exception;

class HomeController extends Controller
{
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct ( ProjectRepository $repository )
    {
        $this->middleware('auth');
        $this->repository = $repository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index ( Request $request ) : View
    {
        auth()->user()->load ([
            'comments' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            },
            'comments.commentable',
            'memberships.group.memberships.member',
        ]);

        return view ('home', [

            'users'   => app (UserRepository::class)->with(['memberships.group', 'memberships.creator'])->all(),
            'project' => $this->repository
                ->pushCriteria ( new VisibleCommentsCriteria ($request->user()) )
                ->first ()
        ]);
    }

    public function store_comment ( StoreProjectComment $request, Project $project ) : RedirectResponse
    {
        $this->authorize ('comment', $project);

        try
        {
            $request->user()
                ->comment ($project, Crypt::encrypt ($request->input ('comment')), 3)
                ->approve();

            return redirect ()->route ('home')->with ('success', 'Comment added successfully.');
        }
        catch ( Exception $e )
        {
            throw $e;
            return redirect ()->route ('home')->withInput()->with ('error', 'Exception: '.$e->getMessage ());
        }
    }

    public function group ( Request $request, Group $group, string $action = 'add', User $user ) : RedirectResponse
    {
        $this->authorize ($action == 'add' ? 'increment' : 'decrement', $group);

        try
        {
            $group->load ('memberships');

            switch ( $action )
            {
                case 'add':

                    if ( $group->memberships->contains ('member_id', $user->id) )
                    {
                        throw new Exception ('User already belongs to this group.');
                    }

                    // Have they already been soft-deleted? Let's check. If they have, we just re-activate them.

                    $previous = app (MembershipRepository::class)
                        ->scopeQuery (function($query) use ($group, $user) {
                            return $query->withTrashed()
                                ->where('group_id', $group->id)
                                ->where('member_id', $user->id);
                        })
                        ->first();

                    if ( $previous )
                    {
                        $previous->restore();
                    }
                    else
                    {
                        $group->memberships ()->create ([
                            'member_type'   => get_class ($user),
                            'member_id'     => $user->id,
                            'role'          => 'default',
                            'approved_at'   => now(),
                            'starts_at'     => now(),
                            'expires_at'    => now()->addYears(10),
                            'created_by'    => $request->user()->id,
                        ]);
                    }

                    return redirect ()->route ('home')->with ('success', 'User added to group.');

                break;

                case 'remove':

                    if ( !$group->memberships->contains ('member_id', $user->id) )
                    {
                        throw new Exception ('User does not belong to this team.');
                    }

                    $group->memberships ()->where ('member_id', $user->id)->delete();

                    return redirect ()->route ('home')->with ('success', 'User removed from group.');

                break;

                default:
                    throw new Exception ('Action specified is not available.');
                break;
            }

        }
        catch ( Exception $e )
        {
            return redirect ()->route ('home')->with ('error', 'Exception: '.$e->getMessage ());
        }
    }

}
