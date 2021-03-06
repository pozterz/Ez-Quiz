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
      	$find = Subject::where('name',trim($data['name']))->where('user_id',Auth::user()->id)->where('subject_number',trim($data['subject_number']))->count();
      	if(!$find){
	      	$subject = new Subject;
	      	$subject->name = trim($data["name"]);
	      	$subject->user_id = Auth::user()->id;
	      	$subject->subject_number = trim($data["subject_number"]);
	      	$subject->save();
	      	$message['type'] ='success';
	      	$message['message'] = $data["subject_number"].' : '.$data["name"] .' : สำเร็จ';
      	}else{
      		$message['type'] ='failed';
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

	 /**
     * get quiz in subject
     * @param  [int] $subject_id [subject id]
     * @return [json Array]  [array(id,name,subject_id,level,start,end,timestamp)]
     */
    public function getSubjectData($id)
    {
    	$subject = Subject::where('user_id',Auth::user()->id)->where('id',$id)->get();
    	if(count($subject)){
	      foreach ($subject[0]->Quiz as $key => $quiz) {
	       $quiz->QuizQa;
	       foreach ($quiz->Answer as $key => $answer) {
	       	$answer->pivot;
	       }
	      }
	      $subject[0]->Member;
	      $type = "success";
      }
      else
      {
      	$subject = 'ไม่พบวิชานี้ในระบบ';
      	$type = "failed";
      }
      return response()
            ->json([
              'result' => $subject,
              'type' => $type
              ]);
    }

	public function getSubjectCount(){
		$subjects = Subject::where('user_id',Auth::user()->id)->count();
		return response()
						->json([
							'result' => $subjects,
							]);
	}

	public function editSubject(Request $request){
		$data = $request->get('data');
		$own = false;
		$message = array('type'=> 'failed','message'=>'');
		$result = array();

		if(strlen($data[0]["subject_number"])  <= 0 || strlen($data[0]["name"]) <= 0 ){
			$message['message'] = 'กรุณากรอกข้อมูลให้ครบถ้วน';
		}
		else{
			foreach (Auth::user()->Subject as $key => $subject)
			{
				if($subject->id == $data[0]["editID"])
				{
					$own = true;
				}
				else
				{
					$message['message'] = 'คุณไม่ใช้เจ้าของวิชานี้';
				}

			}
		}

		if($own)
		{
			$subject = Subject::find($data[0]["editID"]);
			$subject->update([
				'name' => $data[0]['name'],
				'subject_number' => $data[0]['subject_number'],
			]);
			$message['type'] = 'success';
			$message['message'] = 'แก้ไขวิชาสำเร็จ';
		}
		

		array_push($result, $message);
		return response()
            ->json([
              'result' => $result,
              ]);
	}

	public function deleteSubject(Request $request){
		$data = $request->get('data');
		$own = false;
		$message = array('type'=> 'failed','message'=>'');
		$result = array();

		foreach (Auth::user()->Subject as $key => $subject)
		{
			if($subject->id == $data)
			{
				$own = true;
			}
			else
			{
				$message['message'] = 'คุณไม่ใช้เจ้าของวิชานี้';
			}

		}

		if($own)
		{	
			$subject = Subject::find($data);
			$subject->delete();
			$message['type'] = 'failed';
			$message['message'] = 'ลบวิชาสำเร็จ';
		}
		

		array_push($result, $message);
		return response()
            ->json([
              'result' => $result,
              ]);
	}

	public function removeMember(Request $request){
		$message = array('type'=> 'failed','message'=>'');
		$result = array();
		$subject = Subject::find($request->get('data')['subject_id']);
		$user = Subject::find($request->get('data')['subject_id'])->User;

		if($user['id'] == Auth::user()->id)
		{
			if($subject->Member()->detach($request->get('data')['user_id'])){
				$message['type'] = 'success';
				$message['message'] = 'สำเร็จ';
			}
			
		}
		else
		{
			$message['message'] = 'คุณไมไ่ด้เป็นเจ้าของวิชานี้';
		}

		array_push($result,$message);
		return response()
            ->json([
              'result' => $result,
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
    	// 
    	if($quiz['time'] >= 60)
    	{
    		$hour = 1;
    		$minute = 0;
    	}else
    	{
    		$hour = 0;
    		$minute = $quiz['time'];
    	}
    	$newquiz = new Quiz;
    	$newquiz->name = $quiz['name'];
    	$newquiz->subject_id = $quiz['subject_id'];
    	

    	$newquiz->quiz_time = $quiz['time'];
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
	    	$newquestion->save();

	    	// create choice
	    	foreach ($question['choices'] as $key => $choice)
	    	{
					$newchoice = new Choice;
					$newchoice->quiz_qa_id = $newquestion->id;
					$newchoice->text = $choice['text'];
					$newchoice->isCorrect = ($choice['isCorrect'])?'true':'false';
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
