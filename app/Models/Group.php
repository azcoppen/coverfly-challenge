<?php

namespace Coverfly\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Group.
 *
 * @package namespace Coverfly\Models;
 */
class Group extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function creator ()
    {
        return $this->belongsTo (User::class, 'created_by');
    }

    public function memberships ()
    {
        return $this->hasMany (Membership::class);
    }

}
