<?php

namespace Coverfly\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Coverfly\Contracts\Repositories\GroupRepository;
use Coverfly\Models\Group;
use Coverfly\Validators\GroupValidator;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class GroupRepositoryEloquent.
 *
 * @package namespace Coverfly\Repositories;
 */
class GroupRepositoryEloquent extends BaseRepository implements GroupRepository, CacheableInterface
{
    use CacheableRepository;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Group::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
