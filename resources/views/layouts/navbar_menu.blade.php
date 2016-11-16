<header class="nav">
			<div class="container">
				<div class="nav-left">
					<a class="nav-item">
						<img src="{{ url('/images/logo.png') }}" alt="Logo">
					</a>
					<a class="nav-item is-tab {{ Request::is('index')?'is-active':'' }}" href="{{ url('/index') }}">
						<i class="fa fa-home is-tab"></i> &nbsp; หน้าแรก
					</a>
					@if(!Auth::guest())
						
					@endif
				</div>
				<span class="nav-toggle">
					<span></span>
					<span></span>
					<span></span>
				</span>
				@if(Auth::guest())
				<div class="nav-right nav-menu">
					<span class="nav-item">
						@if($i%2)
							<a class="button is-info is-inverted" href="{{ url('/login') }}">
						@else 
							<a class="button is-primary is-inverted" href="{{ url('/login') }}">
						@endif
							<span class="icon">
								<i class="fa fa-sign-in"></i>
							</span>
							<span>เข้าสู่ระบบ</span>
						</a>
						@if($i%2)
							<a class="button is-info is-inverted" href="{{ url('/register') }}">
						@else 
							<a class="button is-primary is-inverted" href="{{ url('/register') }}">
						@endif
							<span class="icon">
								<i class="fa fa-pencil"></i>
							</span>
							<span>สมัครสมาชิก</span>
						</a>
					</span>
				</div>
				@else
					<div class="nav-right nav-menu">
					<span class="nav-item">สวัสดี คุณ {{ Auth::user()->name }}</span>
					@if(Auth::user()->type == 'student')
						<a class="nav-item is-tab  {{ Request::is('Student/Subject')?'is-active':'' }}" href="{{ url('/Student/Subject') }}">
						 <i class="fa fa-bars"></i> &nbsp; วิชาทั้งหมด
						</a>
						<a class="nav-item is-tab {{ Request::is('Student/addSubject')?'is-active':'' }}" href="{{ url('/Student/addSubject') }}">
						 <i class="fa fa-plus"></i> &nbsp; เพิ่มวิชา
						</a>
						@else
						<a class="nav-item is-tab {{ Request::is('Teacher/Subject')?'is-active':'' }}" href="{{ url('/Teacher/Subject') }}">
							 <i class="fa fa-bars"></i> &nbsp; วิชาทั้งหมด
						</a>
						<a class="nav-item is-tab {{ Request::is('Teacher/addSubject')?'is-active':'' }}" href="{{ url('/Teacher/addSubject') }}">
							 <i class="fa fa-plus"></i> &nbsp; เพิ่มวิชา
						</a>
						@endif
					
					<a class="nav-item is-tab {{ Request::is('Profile')?'is-active':'' }}">
						 <i class="fa fa-user"></i> &nbsp; ข้อมูลส่วนตัว
					</a>
					<a class="nav-item is-tab" href="{{ url('/logout') }}">
						 <i class="fa fa-sign-out"></i> &nbsp; ลงชื่อออก
					</a>
					</div>
				@endif
			</div>
		</header>