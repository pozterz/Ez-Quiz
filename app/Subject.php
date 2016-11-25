<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name','subject_number'];
    public function User()
    {
        return $this->BelongsTo(User::class);
    }

    public function Member()
    {
        return $this->BelongsToMany(User::class);
    }

    public function Quiz()
    {
        return $this->hasMany(Quiz::class);
    }
}
