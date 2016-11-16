<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\User;
use App\Quiz;
use App\QuizAnswer;
use App\Subject;
use App\QuizQa;
use Auth;
use Gate;

class TeacherController extends Controller
{
	public function __construct()
	{
		if(Gate::denies('isTeacher',Auth::user())){
			abort(403);
		}
	}

	public function Subject(){
		$subjectcount = Subject::where('user_id',Auth::user()->id)->count();
		return view('app.teacher_subject',compact('subjectcount'));
	}

	public function addSubject(){
		$subjectcount = Subject::where('user_id',Auth::user()->id)->count();
		return view('app.teacher_subject',compact('subjectcount'));
	}

	/**
     * add new subject
     * @param  [Request] $request [request object]
     * @return [json] [response success message , error message]
     */
    public function newSubject(Request $request){
    	$result = array();
    	$message = array('type' => '','message' => '');
      foreach ($request->get('data') as $key => $data) {
      	$find = Subject::where('name',$data['name'])->where('user_id',Auth::user()->id)->where('subject_number',$data['subject_number'])->count();
      	if(!$find){
	      	$subject = new Subject;
	      	$subject->name = $data["name"];
	      	$subject->user_id = Auth::user()->id;
	      	$subject->subject_number = $data["subject_number"];
	      	$subject->save();
	      	$message['type'] ='success';
	      	$message['message'] = $data["subject_number"].' : '.$data["name"] .' : สำเร็จ';
      	}else{
      		$message['type'] ='success';
	      	$message['message'] = $data["subject_number"].' : '.$data["name"] .' : มีวิชานี้อยู่ในระบบแล้ว';
      	}
      	array_push($result,$message);
      }
      return response()
            ->json([
              'result' => $result,
              ]);
    }

	public function getSubjects()
	{
		$subjects = Subject::where('user_id',Auth::user()->id)->get();
		return response()
						->json([
							'result' => $subjects,
							]);
	}

	public function getSubjectCount(){
		$subjects = Subject::where('user_id',Auth::user()->id)->count();
		return response()
						->json([
							'result' => $subjects,
							]);
	}

	public function getQuizzes()
	{
		$subjects = Subject::where('user_id',Auth::user()->id)->get();
		$quizzes = array();
		foreach ($subjects as $key => $subject) {
			$quizzx = $subject->Quiz()->get();
			foreach ($quizzx as $key => $quiz) {
				$quiz["subject"] = $quiz["subject"];
				array_push($quizzes,$quiz);
			}
		}
		return response()
						->json([
							'result' => $quizzes,
							]);
	}

	public function getActiveQuizzes()
	{
		$subjects = Subject::where('user_id',Auth::user()->id)->get();
		$quizzes = array();
		$now = Carbon::now()->toDateTimeString();
		foreach ($subjects as $key => $subject) {
			$quizzx = $subject->Quiz()->get();
			foreach ($quizzx as $key => $quiz) {
				if($quiz->end > $now){
					$quiz["subject"] = $quiz["subject"];
					array_push($quizzes,$quiz);
				}
			}
			
		}
		return response()
						->json([
							'result' => $quizzes,
							]);
	}
	public function getInActiveQuizzes()
	{
		$subjects = Subject::where('user_id',Auth::user()->id)->get();
		$quizzes = array();
		$now = Carbon::now()->toDateTimeString();
		foreach ($subjects as $key => $subject) {
			$quizzx = $subject->Quiz()->get();
			foreach ($quizzx as $key => $quiz) {
				if($quiz->end < $now){
					$quiz["subject"] = $quiz["subject"];
					array_push($quizzes,$quiz);
				}
			}
			
		}
		return response()
						->json([
							'result' => $quizzes,
							]);
	}
	
}
