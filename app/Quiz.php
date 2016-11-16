<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{

    public function Subject()
    {
        return $this->BelongsTo(Subject::class);
    }

    public function QuizAnswer()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function QuizQA()
    {
        return $this->hasMany(QuizQa::class);
    }
}
