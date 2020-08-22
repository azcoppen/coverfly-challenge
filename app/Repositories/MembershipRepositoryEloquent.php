<?php

namespace Coverfly\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Coverfly\Contracts\Repositories\MembershipRepository;
use Coverfly\Models\Membership;
use Coverfly\Validators\MembershipValidator;

/**
 * Class MembershipRepositoryEloquent.
 *
 * @package namespace Coverfly\Repositories;
 */
class MembershipRepositoryEloquent extends BaseRepository implements MembershipRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Membership::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
