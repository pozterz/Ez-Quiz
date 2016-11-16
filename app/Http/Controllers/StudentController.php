<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Gate;

class StudentController extends Controller
{
   	public function __construct()
	{
        if(Gate::denies('isStudent',Auth::user())){
            abort(403);
        }
    }

    public function Subject(){
    	return view('app.student_subject');
    }
}
