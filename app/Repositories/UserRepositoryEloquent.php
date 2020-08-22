<?php

namespace Coverfly\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Coverfly\Contracts\Repositories\UserRepository;
use Coverfly\Models\User;
use Coverfly\Validators\UserValidator;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace Coverfly\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepository, CacheableInterface
{
    use CacheableRepository;
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
