<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Quiz;
use App\QuizAnswer;
use App\Subject;
use App\QuizQa;
use App\Choice;
use App\Http\Requests;

class AppController extends Controller
{
    public function index()
    {
    	return view('index');
    }

    /**
     * get all subjects
     * @return [json Array] [array(id,name,user_id,subject_number,timestamp)]
     */
    public function getSubjects()
   	{
      $subjects = Subject::all();
      foreach ($subjects as $key => $subject) {
        $subject->User;
      }
   		return response()
            ->json([
            	'result' => $subjects,
            	]);
   	}

   	/**
   	 * get subject detail
   	 * @param  [int] $subject_id [subject id]
   	 * @return [json] [id,name,user_id,subject_number,timestamp]
   	 */
   	public function getSubject($subject_id)
   	{
   		return response()
            ->json([
            	'result' => '',
            	]);
   	}

   	/**
   	 * get quiz in subject
   	 * @param  [int] $subject_id [subject id]
   	 * @return [json Array]  [array(id,name,subject_id,level,start,end,timestamp)]
   	 */
   	public function getSubjectQuizzes($id)
   	{
      $subject = Subject::findOrfail($id);
      $quizzes = array();
      $quizzes = $subject->Quiz()->get();
      $subject->user->get();
   		return response()
            ->json([
            	'result' => $quizzes,
              'subject' => $subject,
            	]);
   	}

   	/**
   	 * get teacher's subject
   	 * @param  [int] $teacher_id [teacher id]
   	 * @return [json Array] [array(id,name,user_id,subject_number,timestamp)]
   	 */
   	public function getTeacherSubject($teacher_id)
   	{
   		return response()
            ->json([
            	'result' => '',
            	]);
   	}

   	/**
   	 * get all quiz
   	 * @return [json Array] [array(id,name,subject_id,level,start,end,timestamp)]
   	 */
	public function getQuizzes()
   	{
      $quizzes = Quiz::all();
      foreach ($quizzes as $key => $quiz) {
        $quiz->Subject->get();
      }
      
   		return response()
            ->json([
            	'result' => $quizzes,
            	]);
   	}

      /**
     * get all active quiz : condition end > now
     * @return [json Array] [array(id,name,subject_id,level,start,end,timestamp)]
     */
  public function getActiveQuizzes()
    {
      $now = Carbon::now()->toDateTimeString();
      $quizzes = Quiz::where('end','>',$now)->orderBy('end','asc')->get();
      foreach ($quizzes as $key => $quiz) {
        $quiz->Subject->User->get();
      }
      
      return response()
            ->json([
              'result' => $quizzes,
              ]);
    }
      /**
     * get all inactive quiz : condition end > now
     * @return [json Array] [array(id,name,subject_id,level,start,end,timestamp)]
     */
  public function getInActiveQuizzes()
    {
      $now = Carbon::now()->toDateTimeString();
      $quizzes = Quiz::where('end','<',$now)->orderBy('end','asc')->get();
      foreach ($quizzes as $key => $quiz) {
        $quiz->Subject->get();
      }
      
      return response()
            ->json([
              'result' => $quizzes,
              ]);
    }

   	/**
   	 * get quiz with quiz id
   	 * @param  [int] $quiz_id [quiz id]
   	 * @return [json] [id,name,subject_id,level,start,end,timestamp]
   	 */
   	public function getQuiz($quiz_id)
   	{
   		return response()
            ->json([
            	'result' => '',
            	]);
   	}

   	/******************* other function ******************/

   	/**
   	 * check is user are teacher ?
   	 * @return boolean [true if teacher, false if not teacher]
   	 */
   	private function isTeacher(){
      return Auth::user()->type == 'teacher';
   	}

   	/**
   	 * check is user are student ?
   	 * @return boolean [true if student, false if not student]
   	 */
   	private function isStudent(){
   		return Auth::user()->type == 'student';
   	}
}
