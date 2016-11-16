<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    public function User()
    {
        return $this->BelongsTo(User::class);
    }
}
