@extends('layouts.app') @section('content')

<div class="container">
		<div class="columns">
				<div class="column is-6 is-offset-3">
						<div class="box" style="padding: 20px 20px 50px 20px;">
								<div class="content">
										<h1>เข้าสู่ระบบ</h1>
										<hr>
										<form role="form" method="POST" action="{{ url('/login') }}">
												{{ csrf_field() }}
												<label class="label">ชื่อผู้ใช้</label>
												<p class="control has-icon">
														<input id="username" type="text" class="input {{ $errors->has('username') ? ' is-danger' : '' }}" name="username" value="{{ old('username') }}"> @if ($errors->has('username'))
														<span class="help is-danger">
																{{ $errors->first('username') }}
														</span> @endif
														<i class="fa fa-user"></i>
												</p>
												<label class="label">รหัสผ่าน</label>
												<p class="control has-icon">
														<input id="password" type="password" class="input {{ $errors->has('password') ? ' is-danger' : '' }}" name="password"> @if ($errors->has('password'))
														<span class="help is-danger">
																{{ $errors->first('password') }}
														</span> @endif
														<i class="fa fa-lock"></i>
												</p>
												<input type="hidden" name="ip" value="{{Request::getClientIp()}}">
												<br/>
												<p class="control has-addons has-addons-centered">
														<button type="submit" class="button is-success is-outlined ">
																<i class="fa fa-btn fa-sign-in"></i> &nbsp; เข้าสู่ระบบ
														</button>
														<a href="{{ url('/password/reset') }}" class="button is-primary is-outlined ">
																<i class="fa fa-btn fa-unlock-alt"></i> &nbsp; ลืมรหัสผ่าน ?
														</a>
												</p>
										</form>
								</div>
						</div>
				</div>
		</div>
</div>
<br/><br/><br/><br/><br/><br/><br/>
@endsection
