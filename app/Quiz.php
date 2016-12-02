<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{

    public function Subject()
    {
        return $this->BelongsTo(Subject::class);
    }

    public function Answer()
    {
        return $this->BelongsToMany(User::class)->withPivot('point','spendtime');
    }

    public function QuizQA()
    {
        return $this->hasMany(QuizQa::class);
    }
}
