@extends('layouts.app') @section('content')
<div class="container">
		<div class="columns">
				<div class="column is-6 is-offset-3">
						<div class="box">
								<div class="content">
										<h1>สมัครสมาชิก</h1>
										<hr>
										<div class="panel-body">
												<form role="form" method="POST" action="{{ url('/register') }}">
														{{ csrf_field() }}
														<p class="control">
																<label for="name" class="label">ชื่อผู้ใช้</label>
																<input id="name" type="text" class="input {{ $errors->has('username') ? ' is-danger' : '' }}" name="username" value="{{ old('username') }}" pattern=".{6,}"> @if ($errors->has('username'))
																<span class="help is-danger">
																			{{ $errors->first('username') }}
																		</span> @endif
														</p>
														<p class="control">
																<label for="password" class="label">รหัสผ่าน</label>
																<input id="password" type="password" class="input {{ $errors->has('password') ? ' is-danger' : '' }}" name="password" pattern=".{6,}"> @if ($errors->has('password'))
																<span class="help is-danger">
																				{{ $errors->first('password') }}
																		</span> @endif
														</p>
														<p class="control">
																<label for="password-confirm" class="label">ยืนยันรหัสผ่าน</label>
																<input id="password-confirm" type="password" class="input {{ $errors->has('password_confirmation') ? ' is-danger' : '' }}" name="password_confirmation" pattern=".{6,}"> @if ($errors->has('password_confirmation'))
																<span class="help is-danger">
																				{{ $errors->first('password_confirmation') }}
																		</span> @endif
														</p>
														<p class="control">
																<label for="name" class="label">ชื่อ - นามสกุล</label>
																<input id="name" type="text" class="input {{ $errors->has('name') ? ' is-danger' : '' }}" name="name" value="{{ old('name') }}" pattern=".{10,}"> @if ($errors->has('name'))
																<span class="help is-danger">
																				{{ $errors->first('name') }}
																		</span> @endif
														</p>
														<p class="control">
																<label for="email" class="label">อีเมล์</label>
																<input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ old('email') }}"> @if ($errors->has('email'))
																<span class="help is-danger">
																				{{ $errors->first('email') }}
																		</span> @endif
														</p>
														<p class="control">
																<label for="email" class="label">บทบาท</label>
																<span class="select">
																		<select name="type" id="regist-type">
																			<option value="student">นักเรียน/นักศึกษา</option>
																			<option value="teacher">อาจารย์</option>
																		</select>
																	</span>
														</p>
														<p class="control" id="regist-student_id">
																<label for="student_id" class="label">รหัสนักศึกษา</label>
																<input id="student_id" type="text" class="input {{ $errors->has('student_id') ? ' is-danger' : '' }}" name="student_id" value="{{ old('student_id') }}" pattern="[0-9].{9}"  length="10"> @if ($errors->has('student_id'))
																<span class="help is-danger">
																				{{ $errors->first('student_id') }}
																		</span> @endif
														</p>
														<input type="hidden" name="ip" value="{{Request::getClientIp()}}">
														<br/>
														<p class="control has-addons has-addons-centered">
																<button type="submit" class="button is-success is-outlined is-medium">
																		<i class="fa fa-btn fa-user-plus"></i> &nbsp; สมัครสมาชิก
																</button>
														</p>
												</form>
										</div>
								</div>
						</div>
				</div>
		</div>
		@endsection
