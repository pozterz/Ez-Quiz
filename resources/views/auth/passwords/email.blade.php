@extends('layouts.app')
<!-- Main Content -->
@section('content')
<div class="container">
    <div class="columns">
        <div class="column is-6 is-offset-3">
            <div class="box" style="padding: 20px 20px 50px 20px;">
                <div class="content">
                    <h1>รีเซ็ตรหัสผ่าน</h1>
                    <hr>
                    @if (session('status'))
                    <div class="notification is-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <form role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

                        <label class="label">อีเมล์</label>
                        <p class="control has-icon">
                            <input id="email" type="email" class="input {{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ old('email') }}">
                            <i class="fa fa-at"></i>
                            @if ($errors->has('email'))
                              <span class="help is-danger">
                                  {{ $errors->first('email') }}
                              </span>
                            @endif
                        </p>
                        
                        <p class="control">
                            <button type="submit" class="button is-info is-outlined ">
                                <i class="fa fa-btn fa-envelope"></i> &nbsp; รับลิงค์รีเซ็ตรหัสผ่าน
                            </button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
