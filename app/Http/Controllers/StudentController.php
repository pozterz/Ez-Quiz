<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use App\User;
use App\Quiz;
use App\Subject;
use App\QuizQa;
use App\Choice;
use Auth;
use Gate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StudentController extends Controller
{
   	public function __construct()
		{
        if(Gate::denies('isStudent',Auth::user())){
            abort(403);
        }
    }

    public function Subject()
    {
    	return view('app.student_subject');
    }

    public function addSubject(){
			return view('app.student_subject');
    }
		/**
     * get all subjects
     * @return [json Array] [array(id,name,user_id,subject_number,timestamp)]
     */
    public function getSubjects()
   	{
      $subjects = Subject::all();
      foreach ($subjects as $key => $subject) {
      	$subject['isRegistered'] = $subject->Member->contains(Auth::user()->id);
        $subject->User;
      }
   		return response()
            ->json([
            	'result' => $subjects,
            	]);
   	}

		public function registerSubject(Request $request){
			$id = $request->get('data');
			$result = array();
    	$message = array('type' => '','message' => '');
			//$subject = Subject::findOrfail($id);
			
			try{
				$subject = Subject::findOrfail($id);
			}catch(ModelNotFoundException $ex) {
				$message['type'] = 'failed';
	      $message['message'] = "ไม่พบวิชานี้ในฐานข้อมูล กรุณารีเฟรชแล้วลองใหม่อีกครั้ง";
	      array_push($result,$message);
				return response()
            ->json([
            	'result' => $result,
            	]);
			}
			if($subject->Member->contains(Auth::user()->id)){
				$message['type'] ='failed';
	      $message['message'] = "คุณได้ลงทะเบียนวิชานี้ไปแล้ว";
			}else{
				$subject->Member()->attach(Auth::user()->id);
				$message['type'] ='success';
	      $message['message'] = "ลงทะเบียนวิชา ". $subject->name . " สำเร็จ";
			}
			array_push($result,$message);
			return response()
							->json([
								'result' => $result,
								]);
		}

		public function removeSubject(Request $request){
			$id = $request->get('data');
			$result = array();
    	$message = array('type' => '','message' => '');
			//$subject = Subject::findOrfail($id);
			
			try{
				$subject = Subject::findOrfail($id);
			}catch(ModelNotFoundException $ex) {
				$message['type'] = 'failed';
	      $message['message'] = "ไม่พบวิชานี้ในฐานข้อมูล กรุณารีเฟรชแล้วลองใหม่อีกครั้ง";
	      array_push($result,$message);
				return response()
            ->json([
            	'result' => $result,
            	]);
			}
			if($subject->Member->contains(Auth::user()->id)){
				$subject->Member()->detach(Auth::user()->id);
				$message['type'] ='removed';
	      $message['message'] = "ออกจากวิชา ". $subject->name . " สำเร็จ";
			}else{
				$message['type'] ='failed';
	      $message['message'] = "คุณไม่ได้ลงทะเบียนวิชานี้";
			}
			array_push($result,$message);
			return response()
							->json([
								'result' => $result,
								]);
		}

    public function getRegisteredSubjects()
		{
			$subjects = User::find(Auth::user()->id)->Member()->get();
			foreach ($subjects as $key => $subject) {
				$subject->User;
				$subject->Member;
				$subject->Quiz;
			}
			return response()
							->json([
								'result' => $subjects,
								]);
		}

		public function getRegisteredSubjectsQuizzes()
		{
			$registeredSubject = User::find(Auth::user()->id)->Member()->get();
			$result = array();
			$count = 0;
			foreach ($registeredSubject as $key => $subject) {
				$quiz = Subject::find($subject->id);
				$count += $quiz->Quiz->count();
				foreach ($quiz->Quiz as $key => $quizx) {
					$quiz["quiz"] = $quizx->QuizQA;
					$quizx["isAnswered"] = $quizx->Answer->contains(Auth::user()->id);
					foreach ($quiz["quiz"] as $key => $question)
					{
						$quiz["quiz_q_a"] = $question->Choice;
					}

				}
				
				array_push($result,$quiz);
			}
			return response()
							->json([
								'result' => $result,
								'count' => $count,
								]);
		}
		public function answerQuiz($id){
			$errr = false;

			try{
				$Quiz = Quiz::findOrfail($id);
			}catch(ModelNotFoundException $ex) {
	      $errr = "ไม่พบแบบทดสอบนี้ในฐานข้อมูล กรุณารีเฟรชแล้วลองใหม่อีกครั้ง";
				return view('app.answer_quiz',compact('errr','id'));
			}

			if(Carbon::now()->timestamp > strtotime($Quiz->end))
			{
				$errr = "แบบทดสอบนี้สิ้นสุดลงแล้ว";
			}

			if(!$Quiz->Subject->Member->contains(Auth::user()->id))
			{
				$errr = "คุณไม่ได้ลงทะเบียนวิชานี้";
			}
			
			if($Quiz->Answer->contains(Auth::user()->id))
			{
				$errr = "คุณได้ทำแบบทดสอบนี้ไปแล้ว";
			}

			return view('app.answer_quiz',compact('errr','id'));
		}

		public function sendAnswer(Request $request){
			$data = $request->get('data');
			$result = '';
			$type = false;
			try{
				$quiz = Quiz::findOrfail($data["quiz_id"]);
			}catch(ModelNotFoundException $ex) {
	      $result = "ไม่พบแบบทดสอบนี้ในฐานข้อมูล กรุณารีเฟรชแล้วลองใหม่อีกครั้ง";
				return response()
            ->json([
            	'result' => $result,
							'type' => $type,
            	]);
			}

			// check answered ?
			if($quiz->Answer->contains(Auth::user()->id))
			{
				$result = 'คุณได้ทำแบบทดสอบนี้ไปแล้ว';
			}
			else{
				// check answer
				$points = 0;
				foreach ($quiz->QuizQa as $key => $question) 
				{
					foreach ($data["data"] as $key => $answer) 
					{
						if($question->id == $answer["question_id"])
						{
							foreach ($question->Choice as $key => $choice) 
							{
								if($answer["answer"] == $choice->id)
								{
									if($choice->isCorrect)
									{
										$points += 1;
										break;
									}
								}
							}
						}
					}
				}
				
				$time = ($quiz->quiz_time*60)-($data["minutes"]*60+$data["seconds"]);
				$quiz->Answer()->attach(Auth::user()->id,['point'=>$points,'spendtime'=>$time]);
				$caled_result = array();
				$caled_result["points"] = $points;
				$caled_result["minutes"] = intval(floor($time/60));
				$caled_result["seconds"] = $time%60;
				$result = array();
				array_push($result,$caled_result);
				$type = true;
			}

			return response()
							->json([
            	'result' => $result,
								'type' => $type,
            	]);
		}


}
