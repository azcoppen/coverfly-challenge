<?php

namespace Coverfly\Providers;

use Illuminate\Support\ServiceProvider;

use Coverfly\Contracts\Repositories\GroupRepository;
use Coverfly\Repositories\GroupRepositoryEloquent;

use Coverfly\Contracts\Repositories\MembershipRepository;
use Coverfly\Repositories\MembershipRepositoryEloquent;

use Coverfly\Contracts\Repositories\ProjectRepository;
use Coverfly\Repositories\ProjectRepositoryEloquent;

use Coverfly\Contracts\Repositories\UserRepository;
use Coverfly\Repositories\UserRepositoryEloquent;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind (GroupRepository::class, GroupRepositoryEloquent::class);
        $this->app->bind (MembershipRepository::class, MembershipRepositoryEloquent::class);
        $this->app->bind (ProjectRepository::class, ProjectRepositoryEloquent::class);
        $this->app->bind (UserRepository::class, UserRepositoryEloquent::class);
        //:end-bindings:
    }
}
