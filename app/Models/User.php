<?php

namespace Coverfly\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Actuallymab\LaravelComment\CanComment;
use Lab404\Impersonate\Models\Impersonate;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements Transformable, JWTSubject
{
    use Notifiable, TransformableTrait, LogsActivity, CanComment, SoftDeletes, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function creator ()
    {
        return $this->belongsTo (User::class, 'created_by');
    }

    public function memberships ()
    {
        return $this->morphMany (Membership::class, 'member')
            ->whereNotNull ('approved_at')
            ->whereNull ('removed_at')
            ->where ('approved_at', '<', now())
            ->where ('starts_at', '<', now())
            ->where ('expires_at', '>', now());
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
