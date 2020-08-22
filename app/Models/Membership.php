<?php

namespace Coverfly\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Membership.
 *
 * @package namespace Coverfly\Models;
 */
class Membership extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected $dates = ['approved_at', 'rejected_at', 'starts_at', 'expires_at'];

    public function creator ()
    {
        return $this->belongsTo (User::class, 'created_by');
    }

    public function group ()
    {
        return $this->belongsTo (Group::class);
    }

    public function member ()
    {
        return $this->morphTo();
    }

}
