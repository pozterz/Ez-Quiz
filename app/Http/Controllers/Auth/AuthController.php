<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/index';
    protected $redirectAfterLogout = '/index';
    protected $username = 'username';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $message = [
            'required' => 'กรุณากรอกข้อมูลในฟิลด์นี้',
            'username.min' => 'Username ต้องกรอกอย่างน้อย 6 ตัวอักษร',
            'username.max' => 'Username สามารถกรอกได้ทั้งหมด 100 ตัวอักษร',
            'username.unique' => 'Username นี้มีผู้ใช้แล้ว',
            'name.min' => 'ชื่อ-นามสกุล ต้องกรอกอย่างน้อย 10 ตัวอักษร',
            'name.max' => 'ชื่อ-นามสกุล สามารถกรอกได้ทั้งหมด 100 ตัวอักษร',
            'email.max' => 'อีเมล์ สามารถกรอกได้ทั้งหมด 100 ตัวอักษร',
            'email.unique' => 'email นี้มีผู้ใช้แล้ว',
            'password.min' => 'รหัสผ่าน จะต้องกรอกอย่างน้อย 6 ตัวอักษร',
            'password.max' => 'รหัสผ่าน จะสามารถกรอกได้ทั้งหมด 100 ตัวอักษร',
            'password.confirmed' => 'ยืนยันรหัสผ่านต้องตรงกับรหัสผ่าน',
            'password.different' => 'ชื่อผู้ใช้และรหัสผ่านห้ามตรงกัน',
            'student_id.min' => 'รหัสนักศึกษา จะต้องกรอกอย่างน้อย 4 ตัวอักษร',
            'student_id.max' => 'รหัสนักศึกษา จะสามารถกรอกได้ทั้งหมด 10 ตัวอักษร',
            'student_id.regex' => 'รหัสนักศึกษา จะต้องกรอกเป็นตัวเลขเท่านั้น',
        ];
        return Validator::make($data, [
            'username' => 'required|min:6|max:30|unique:users',
            'name' => 'required|min:10|max:100',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|different:username|confirmed|min:6|max:200',
            'type' => 'required|in:student,teacher',
            'student_id' => 'regex:/\d/|min:4|max:10',
            'ip' => 'ip',
        ],$message);
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }
        if($request->get('type') == 'teacher')
        {
            $request->student_id = '';
        }
        $user = $this->create($request->all());

        Auth::guard($this->getGuard())->login($user);
        return redirect($this->redirectPath());
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
            'email' => $data['email'],
            'name' => $data['name'],
            'type' => $data['type'],
            'student_id' => $data['student_id'],
            'ip' => $data['ip'],
        ]);
    }

    protected function getFailedLoginMessage(){
        return 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง';
    }
}
