<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','username','type','student_id','ip'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    public function hasRole($role)
    {
        return $this->type === $role;
    }
    public function Subject()
    {
        return $this->hasMany(Subject::class);
    }

    public function Member()
    {
        return $this->BelongsToMany(Subject::class);
    }

    public function Answer()
    {
        return $this->BelongsToMany(Quiz::class)->withPivot('point','spendtime');
    }

}
