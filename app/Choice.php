<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{	
	 protected $hidden = [
        'isCorrect',
    ];

   public function QuizQa()
   {
        return $this->BelongsTo(QuizQa::class);
   }
}
