<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizQa extends Model
{
  public function Choice()
   {
      return $this->hasMany(Choice::class);
   }
}
