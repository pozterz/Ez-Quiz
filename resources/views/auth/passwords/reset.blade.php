@extends('layouts.app') @section('content')
<div class="container">
		<div class="columns">
				<div class="column is-6 is-offset-3">
						<div class="box" style="padding: 20px 20px 50px 20px;">
								<div class="content">
										<h1>รีเซ็ตรหัสผ่าน</h1>
										<hr>
										<form role="form" method="POST" action="{{ url('/password/reset') }}">
												{{ csrf_field() }}
												<input type="hidden" name="token" value="{{ $token }}">

												<label class="label">อีเมล์</label>
												<p class="control has-icon">
														<input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ $email or old( 'email') }} ">
														<i class="fa fa-at"></i>
															@if ($errors->has('email'))
																	<span class="help is-danger ">
																			{{ $errors->first('email') }}
																	</span>
															@endif
														
												</p>

												
												<label for="password" class="label">รหัสผ่าน</label>
												<p class="control has-icon">
														<input id="password" type="password" class="input {{ $errors->has('password') ? ' is-danger' : '' }}" name="password">
															<i class="fa fa-lock"></i>
															@if ($errors->has('password'))
																<span class="help is-danger">
																	{{ $errors->first('password') }}
																</span>
															@endif
												</p>

												<label for="password" class="label">ยืนยันรหัสผ่าน</label>
												<p class="control has-icon">
														<input id="password-confirm" type="password" class="input {{ $errors->has('password_confirmation') ? ' is-danger' : '' }}" name="password_confirmation">
														<i class="fa fa-lock"></i>
														@if ($errors->has('password_confirmation'))
																<span class="help is-danger">
																		{{ $errors->first('password_confirmation') }}
																</span>
														@endif
												</p>
												<p class="control">
														<button type="submit" class="button is-success is-outlined">
																<i class="fa fa-btn fa-refresh"></i> &nbsp; รีเซ็ตรหัสผ่าน
														</button>
												</p>
										</form>
								</div>
						</div>
				</div>
		</div>
</div>
@endsection
