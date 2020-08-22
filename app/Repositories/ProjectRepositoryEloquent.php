<?php

namespace Coverfly\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Coverfly\Contracts\Repositories\ProjectRepository;
use Coverfly\Models\Project;
use Coverfly\Validators\ProjectValidator;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;
/**
 * Class ProjectRepositoryEloquent.
 *
 * @package namespace Coverfly\Repositories;
 */
class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepository, CacheableInterface
{
    use CacheableRepository;
    
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Project::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

}
