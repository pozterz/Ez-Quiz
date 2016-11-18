<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
	Route::auth();
	Route::get('/', 'AppController@index');
	Route::get('/index', 'AppController@index');
	Route::get('/test', 'AppController@index2');
	Route::get('/getSubjects', 'AppController@getSubjects');
	Route::get('/getSubject/{subject_id}', 'AppController@getSubject');
	Route::get('/getSubjectQuizzes/{id}', 'AppController@getSubjectQuizzes');
	Route::get('/getQuizzes', 'AppController@getQuizzes');
	Route::get('/getActiveQuizzes', 'AppController@getActiveQuizzes');
	Route::get('/getInActiveQuizzes', 'AppController@getInActiveQuizzes');
	Route::get('/getQuiz/{id}', 'AppController@getQuiz');

	// teacher route
	Route::get('/Teacher/Subject','TeacherController@Subject');
	Route::get('/Teacher/getSubjects', 'TeacherController@getSubjects');
	Route::get('/Teacher/getSubjectCount', 'TeacherController@getSubjectCount');
	Route::get('/Teacher/addSubject', 'TeacherController@addSubject');
	Route::post('/Teacher/newSubject', 'TeacherController@newSubject');
	Route::get('/Teacher/getQuizzes', 'TeacherController@getQuizzes');
	Route::post('/Teacher/newQuizzes', 'TeacherController@newQuizzes');
	Route::get('/Teacher/getActiveQuizzes', 'TeacherController@getActiveQuizzes');
	Route::get('/Teacher/getInActiveQuizzes', 'TeacherController@getInActiveQuizzes');

	// student route
	Route::get('/Student/Subject','StudentController@Subject');
	Route::get('/Student/getSubjects', 'StudentController@getSubjects');
	Route::get('/Student/addSubject', 'StudentController@addSubject');
	Route::post('/Student/registerSubject', 'StudentController@registerSubject');
	Route::post('/Student/removeSubject', 'StudentController@removeSubject');
	Route::get('/Student/getRegisteredSubjects', 'StudentController@getRegisteredSubjects');
	Route::get('/Student/getRegisteredSubjectsQuizzes', 'StudentController@getRegisteredSubjectsQuizzes');
	Route::get('/Student/answerQuiz/{id}','StudentController@answerQuiz');
});

