<?php

namespace Coverfly\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Database\Eloquent\SoftDeletes;
use Actuallymab\LaravelComment\Contracts\Commentable;
use Actuallymab\LaravelComment\HasComments;

/**
 * Class Project.
 *
 * @package namespace Coverfly\Models;
 */
class Project extends Model implements Transformable, Commentable
{
    use TransformableTrait, HasComments, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function creator ()
    {
        return $this->belongsTo (User::class, 'created_by');
    }
    
}
