<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
   public function QuizQa()
   {
        return $this->BelongsTo(QuizQa::class);
   }
}
