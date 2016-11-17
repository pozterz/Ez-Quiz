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
use App\Choice;
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
		$subjects = Subject::where('user_id',Auth::user()->id)->get();
		$quizcount = 0;
		foreach ($subjects as $key => $subject) {
			$quizcount += $subject->Quiz()->count();
		}
		return view('app.teacher_subject',compact('subjectcount','quizcount'));
	}

	public function addSubject(){
		$subjectcount = Subject::where('user_id',Auth::user()->id)->count();
		$subjects = Subject::where('user_id',Auth::user()->id)->get();
		$quizcount = 0;
		foreach ($subjects as $key => $subject) {
			$quizcount += $subject->Quiz()->count();
		}
		return view('app.teacher_subject',compact('subjectcount','quizcount'));
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

	/**
     * add new subject
     * @param  [Request] $request [request object]
     * @return [json] [response success message , error message]
     */
  public function newQuizzes(Request $request)
  {
  	$result = array();
    $message = array('type' => '','message' => '');
    $error = false;

    $quiz = $request->get('data');
    //dd($quiz);
    if($quiz['name'] == '' || strlen($quiz['name']) == 0)
    {
    	$message['type'] = 'failed';
    	$message['message'] .= 'กรุณากรอกชื่อแบบทดสอบ';
    	$error = true;
    }
    if($quiz['level'] > 6 && $quiz['level'] < 1)
    {
    	$message['type'] = 'failed';
    	$message['message'] .= 'กรุณาระบุระดับความยาก';
    	$error = true;
    }
    if(strtotime($quiz['start']) > strtotime($quiz['end']))
    {
    	$message['type'] = 'failed';
    	$message['message'] .= 'กรุณากำหนดเวลาเริ่มต้นและสิ้นสุดให้ถูกต้อง';
    	$error = true;
    }

 		if($quiz['starttime'] == '') $quiz['starttime'] = '00:00';
    if($quiz['endtime'] == '') $quiz['endtime'] = '00:00';

    $start = $this->ConvertDate(date("Y-m-d",strtotime($quiz['start'])),date("H:i",strtotime($quiz['starttime'])));
    $end = $this->ConvertDate(date("Y-m-d",strtotime($quiz['end'])),date("H:i",strtotime($quiz['endtime'])));
    
    
    if(!$error)
    {
	    if(isset($quiz['question']))
	    {
		    foreach ($quiz['question'] as $key => $question)
		    {
		    	if($question['ask'] == '' || strlen($question['ask']) == 0)
		    	{
			    	$message['type'] = 'failed';
			    	$message['message'] .= 'กรุณากรอกคำถามให้ครบถ้วน';
			    	$error = true;
			    }

			    if(intval($question['answer']) < 0)
		    	{
			    	$message['type'] = 'failed';
			    	$message['message'] .= 'กรุณาเลือกคำตอบที่ถูกต้องให้ครบถ้วน';
			    	$error = true;
			    }
			    
			    if(!$error)
			    {
			    	
				    if(isset($question['choices']))
				    {
				    	foreach ($question['choices'] as $key => $choice) {
				    		if($choice['text'] == '' || strlen($choice['text']) == 0)
				    		{
				    			$message['type'] = 'failed';
						    	$message['message'] .= 'กรุณากรอกตัวเลือกให้ครบถ้วน';
						    	$error = true;
				    		}
				    	}
				    }
				    else
				    {
							$message['type'] = 'failed';
				    	$message['message'] .= 'กรุณากำหนดตัวเลือกให้ครบถ้วน';
				    	$error = true;
				    }
			  	}
		    }
	    }
	    else
	    {
	    	$message['type'] = 'failed';
	    	$message['message'] .= 'กรุณากรอกคำถามให้ครบถ้วน';
	    	$error = true;
	    }
    }

    if(!$error){
    	// create Quiz
    	$newquiz = new Quiz;
    	$newquiz->name = $quiz['name'];
    	$newquiz->subject_id = $quiz['subject_id'];
    	$newquiz->level = $quiz['level'];
    	$newquiz->start = $start;
    	$newquiz->end = $end;
    	$newquiz->save();


    	// create QuizQa
    	foreach ($quiz['question'] as $key => $question)
		  {
				$newquestion = new QuizQa;
	    	$newquestion->quiz_id = $newquiz->id;
	    	$newquestion->question = $question['ask'];
	    	$newquestion->duration = 60;
	    	$newquestion->save();

	    	// create choice
	    	foreach ($question['choices'] as $key => $choice)
	    	{
					$newchoice = new Choice;
					$newchoice->quiz_qas_id = $newquestion->id;
					$newchoice->text = $choice['text'];
					$newchoice->isCorrect = $choice['isCorrect'];
					$newchoice->save();
				}
    	}

    	$message['type'] = 'success';
	    $message['message'] .= 'สร้างแบบทดสอบสำเร็จ !';
    }
    array_push($result,$message);
    return response()
						->json([
							'result' => $result,
							]);
  }

	

	/******************* other function ******************/

	private function ConvertDate($date,$time){
        $split = explode(':',$time);
        if(count($split) != 2){
            $split = array();
            $split[0] = 0;
            $split[1] = 0;
        }
        $end_time = Carbon::parse($date)
            ->startOfDay()
            ->addHours($split[0])
            ->addMinutes($split[1])
            ->toDateTimeString();
        return $end_time;
    }
	
}
