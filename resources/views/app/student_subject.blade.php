@extends('app.template') @section('content')
<div class="container" ng-app="Student">
	<div class="tile is-ancestor" ng-controller="PanelController as panel">
		<div class="tile is-vertical is-3 is-parent">
			<div class="tile is-child box">
				<aside class="menu">
					<p class="menu-label">
						วิชา
					</p>
					<ul class="menu-list">
						<li>
							<a href="#" ng-click="panel.selectTab(1)" ng-class="{ 'is-active':panel.isSelected(1) }">
								<i class="icon is-small fa fa-search"></i> ลงทะเบียนวิชา
							</a>
						</li>
						<li>
							<a href="#" ng-click="panel.selectTab(2);" ng-class="{ 'is-active':panel.isSelected(2) }">
								<i class="icon is-small fa fa-clone"></i> วิชาที่ลงทะเบียนแล้ว <span class="tag is-danger is-small" ng-model="subjects.length"><% subjects.length %></span>
							</a>
						</li>
					</ul>
					<p class="menu-label">
						รายวิชาที่ลงทะเบียนแล้ว
					</p>
					<ul class="menu-list">
						<li>
							<ul>
								<li ng-show="!subjects.length">
									<a href="#">ไม่พบวิชาที่ลงทะเบียนไว้แล้ว</a>
								</li>
								<li ng-repeat="subject in subjects">
									<a href="#" ng-click="panel.getSubjectQuiz(subject.id); panel.selectTab(3);"> <% subject.name  | limitTo: 25 %><%subject.name.length > 25 ? '...' : ''%>
										<span class="tag is-light-blue is-small"  ng-model="subject.quiz.length"><% subject.quiz.length %></span>
									</a>
								</li>
							</ul>
						</li>
					</ul>
					<p class="menu-label">
						แบบทดสอบ
					</p>
					<ul class="menu-list">
						<li>
							<a href="#" ng-click="panel.selectTab(4)" ng-class="{ 'is-active':panel.isSelected(4) }">
								<i class="icon is-small fa fa-check-square-o"></i> แบบทดสอบที่ทำไปแล้ว
							</a>
						</li>
						<li>
							<a href="#" ng-click="panel.selectTab(5);" ng-class="{ 'is-active':panel.isSelected(5) }">
							<i class="icon is-small fa fa-book"></i> แบบทดสอบทั้งหมด <span class="tag is-danger is-small" ng-model="QuizCount"><% QuizCount %></span>
							</a>
							<ul>
								<li>
									<a href="#" ng-click="panel.selectTab(6)" ng-class="{ 'is-active':panel.isSelected(6) }">กำลังทำงาน</a>
								</li>
								<li>
									<a href="#" ng-click="panel.selectTab(7)" ng-class="{ 'is-active':panel.isSelected(7) }">สิ้นสุดแล้ว</a>
								</li>
							</ul>
						</li>
					</ul>
				</aside>
			</div>
		</div>
	<!-- 	add subject -->
		<div class="tile is-parent" ng-show="panel.isSelected(1)">
			<div class="tile is-child box">
				<article class="media">
					<div class="media-content">
						<div class="content">
							<div class="columns">
								<div class="column">
									<p class="control has-icon is-pulled-right">
										<input type="text" name="search" class="input is-info is-outlined" ng-model="search" placeholder="ค้นหาวิชา">
										<i class="fa fa-search"></i>
									</p>
								</div>
							</div>
							<br/>
							<div class="notification fade" ng-show="notification" ng-class="(post_datas[0].type == 'success')?'is-success':'is-danger'">
								<button class="delete" ng-click="notification = false"></button>
									<p ng-repeat="message in post_datas">
										<% message.message %>
									</p>
							</div>
							<div class="notification is-danger" ng-show="!datas.length">
								ไม่พบวิชาในฐานข้อมูล
							</div>
							<div class="columns">
								<div class="column">
						 			<div class="box" dir-paginate="subject in datas | filter:search | itemsPerPage: pageSize" current-page="currentPage" pagination-id="addSubject">
						 				<h4>
						 					<strong><% subject.subject_number %> : <% subject.name %></strong>
						 				</h4>
						 				<h6>
						 					<ul>
						 						<li>เจ้าของวิชา : <% subject.user.name %></li>
						 						<li>สร้างเมื่อ : <% panel.convertTime(subject.created_at) | date:'EEEEที่ d MMMM y HH:mm น.' %> </li>
						 						<li>สมาชิกทั้งหมด : <% subject.member.length %> คน</li>
						 					</ul>
						 				</h6>
						 				<button ng-if="!subject.isRegistered" ng-click="panel.addSubject(subject.id,'Student/getSubjects')" type="button" class="button is-success is-outlined"><i class="fa fa-plus"></i> &nbsp; ลงทะเบียน</button>
						 				<button ng-if="subject.isRegistered" ng-click="panel.rmSubject(subject.id,'Student/getSubjects')" type="button" class="button is-danger is-outlined"><i class="fa fa-times"></i> &nbsp; ออกจากวิชานี้</button>
						 			</div>
						 		</div>
						 </div>
						</div>
						<br/>
						<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="../dirPagination.tpl.html" pagination-id="addSubject"></dir-pagination-controls>
					</div>
				</article>
			</div>
		</div>
		<!-- 	registered subject -->
		<div class="tile is-parent" ng-show="panel.isSelected(2)">
			<div class="tile is-child box">
				<article class="media">
					<div class="media-content">
						<div class="content">
							<div class="columns" ng-show="subjects.length">
								<div class="column">
									<p class="control has-icon is-pulled-right">
										<input type="text" name="search" class="input is-info is-outlined" ng-model="search" placeholder="ค้นหาวิชา">
										<i class="fa fa-search"></i>
									</p>
								</div>
							</div>
							<br/>
							<div class="notification fade" ng-show="notification" ng-class="(post_datas[0].type == 'success')?'is-success':'is-danger'">
								<button class="delete" ng-click="notification = false"></button>
									<p ng-repeat="message in post_datas">
										<% message.message %>
									</p>
							</div>
							<div class="notification is-danger" ng-show="!datas.length && !loading">
								ไม่พบวิชาที่ลงทะเบียนไว้แล้ว
							</div>
							<div class="columns">
								<div class="column">
						 			<div class="box" dir-paginate="subject in datas | filter:search:strict | itemsPerPage: pageSize" current-page="currentPage" pagination-id="RegistSubject">
						 				<h4>
						 					<strong><% subject.subject_number %> : <% subject.name %></strong>
						 				</h4>
						 				<h6>
						 					<ul>
						 						<li>เจ้าของวิชา : <% subject.user.name %></li>
						 						<li>สร้างเมื่อ : <% panel.convertTime(subject.created_at) | date:'EEEEที่ d MMMM y HH:mm น.' %> </li>
						 						<li>สมาชิกทั้งหมด : <% subject.member.length %> คน</li>
						 					</ul>
						 				</h6>
						 				<button ng-click="panel.rmSubject(subject.id,'Student/getRegisteredSubjects')" type="button" class="button is-danger is-outlined"><i class="fa fa-times"></i> &nbsp; ออกจากวิชานี้</button>
						 			</div>
						 		</div>
						 </div>
						</div>
						<br/>
						<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="../dirPagination.tpl.html" pagination-id="RegistSubject"></dir-pagination-controls>
					</div>
				</article>
			</div>
		</div>
		<!-- subject quiz -->
		<div class="tile is-parent" ng-show="panel.isSelected(3)">
			<div class="tile is-child box">
				<article class="media">
					<div class="media-content">
						<div class="content">
							<loading></loading>
							<p>
								<h4><% subject.subject_number %> : <% subject.name %></h4>
								<h6>เจ้าของวิชา : <% subject.user.name %> </h6>
							</p>
							<div class="columns" ng-show="subjectquiz.length">
								<div class="column is-5">
									<p class="control has-icon">
										<input type="text" class="input is-outlined is-info" ng-model="searchquiz.name" placeholder="ค้นหาแบบทดสอบ"><br/>
										<i class="fa fa-search"></i>
									</p>
								</div>
							</div>
							<div class="notification is-danger" ng-show="!subjectquiz.length">
								ไม่พบแบบทดสอบของวิชานี้
							</div>
						 	<div class="box" ng-repeat="quiz in subjectquiz | filter:searchquiz:strict | orderBy:'-created_at'">
						 		<div class="columns">
						 			<div class="column is-10">
						 				<strong> <% quiz.name %> </strong>
						 				<span class="tag is-info is-small">
								 			<i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i>
								 		</span>
						 				<span class="tag is-small" ng-class="panel.compareFromNow(quiz.end,'>')?'is-danger':'is-success'"><%panel.compareFromNow(quiz.end,'>')?'สิ้นสุดแล้ว':'กำลังทำงาน'%></span>
						 					<span class="tag is-small is-dark" ng-show="quiz.isAnswered"><i class="fa fa-check"></i> &nbsp; ทำแล้ว : <% quiz.points %>/<% quiz.quiz_count %> คะแนน</span>
							 		</div>
						 		</div>
					 			<div class="columns">
					 				<div class="column">
					 					<ul>
					 						<li>วันที่เริ่มต้น : <% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %></li>
					 						<li>วันที่สิ้นสุด : <% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %> </li>
					 						<li>จำนวน : <% quiz.quiz_count %> ข้อ</li>
					 						<li>เวลา : <% quiz.quiz_time %> นาที</li>
					 					</ul>
					 					<a class="button is-info is-outlined" ng-class="{'is-disabled':panel.compareFromNow(quiz.end,'>') || quiz.isAnswered }" href="{{url('/Student/answerQuiz')}}/<%quiz.id%>" target="_blank">
						 						<i class="fa fa-check"> ทำแบบทดสอบ</i>
						 					</a>
								 	</div>
					 			</div>
						 	</div>
						</div>
					</div>
				</article>
			</div>
		</div>
		<!-- answered quiz -->
		<div class="tile is-parent" ng-show="panel.isSelected(4)">
			<div class="tile is-child box">
				<article class="media">
					<div class="media-content" ng-show="loading">
						<loading></loading>
					</div>
					<div class="media-content" ng-show="!loading">
						<div class="columns" ng-show="allQuizzes.length">
							<div class="column is-5">
								<p class="control has-icon">
									<input type="text" class="input is-outlined is-info" ng-model="searchquiz.name" placeholder="ค้นหาแบบทดสอบ"><br/>
									<i class="fa fa-search"></i>
								</p>
							</div>
						</div>
						<div class="notification is-danger" ng-show="!allQuizzes.length && !loading">
								ไม่พบแบบทดสอบ
						</div>
						<div class="content" ng-repeat="subjects in allQuizzes">
						 	<div class="box" ng-repeat="quiz in subjects.quiz | filter:searchquiz:strict | orderBy:'-created_at'" ng-show="quiz.isAnswered">
						 		<div class="columns">
						 			<div class="column is-10">
						 				<strong> <% quiz.name %> </strong>
						 				<span class="tag is-info is-small">
								 			<i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i>
								 		</span>
						 				<span class="tag is-small" ng-class="panel.compareFromNow(quiz.end,'>')?'is-danger':'is-success'"><%panel.compareFromNow(quiz.end,'>')?'สิ้นสุดแล้ว':'กำลังทำงาน'%></span>
						 				<span class="tag is-small is-dark" ng-show="quiz.isAnswered"><i class="fa fa-check"></i> &nbsp; ทำแล้ว : <% quiz.points %>/<% quiz.quiz_q_a.length %> คะแนน</span>
							 		</div>
						 		</div>
					 			<div class="columns">
					 				<div class="column">
					 						<ul>
						 						<li>วิชา : <% subjects.name %></li>
						 						<li>วันที่เริ่มต้น : <% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>วันที่สิ้นสุด : <% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>จำนวน : <% quiz.quiz_q_a.length %> ข้อ.</li>
						 						<li>เวลา : <% quiz.quiz_time %> นาที.</li>
						 					</ul>
						 					<a class="button is-info is-outlined" ng-class="{'is-disabled':panel.compareFromNow(quiz.end,'>') || quiz.isAnswered }" href="{{url('/Student/answerQuiz')}}/<%quiz.id%>" target="_blank">
						 						<i class="fa fa-check"> ทำแบบทดสอบ</i>
						 					</a>
								 	</div>
					 			</div>
						 	</div>
						</div>
					</div>
				</article>
			</div>
		</div>
		<!-- all quiz -->
		<div class="tile is-parent" ng-show="panel.isSelected(5)">
			<div class="tile is-child box">
				<article class="media">
					<div class="media-content" ng-show="loading">
						<loading></loading>
					</div>
					<div class="media-content" ng-show="!loading">
						<div class="columns" ng-show="allQuizzes.length">
							<div class="column is-5">
								<p class="control has-icon">
									<input type="text" class="input is-outlined is-info" ng-model="searchquiz.name" placeholder="ค้นหาแบบทดสอบ"><br/>
									<i class="fa fa-search"></i>
								</p>
							</div>
						</div>
						<div class="notification is-danger" ng-show="!allQuizzes.length && !loading">
								ไม่พบแบบทดสอบ
						</div>
						<div class="content" ng-repeat="subjects in allQuizzes">
						 	<div class="box" ng-repeat="quiz in subjects.quiz | filter:searchquiz:strict | orderBy:'-created_at'">
						 		<div class="columns">
						 			<div class="column is-10">
						 				<strong> <% quiz.name %> </strong>
						 				<span class="tag is-info is-small">
								 			<i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i>
								 		</span>
						 				<span class="tag is-small" ng-class="panel.compareFromNow(quiz.end,'>')?'is-danger':'is-success'"><%panel.compareFromNow(quiz.end,'>')?'สิ้นสุดแล้ว':'กำลังทำงาน'%></span>
						 				<span class="tag is-small is-dark" ng-show="quiz.isAnswered"><i class="fa fa-check"></i> &nbsp; ทำแล้ว : <% quiz.points %>/<% quiz.quiz_q_a.length %> คะแนน</span>
							 		</div>
						 		</div>
					 			<div class="columns">
					 				<div class="column">
					 						<ul>
						 						<li>วิชา : <% subjects.name %></li>
						 						<li>วันที่เริ่มต้น : <% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>วันที่สิ้นสุด : <% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>จำนวน : <% quiz.quiz_q_a.length %> ข้อ.</li>
						 						<li>เวลา : <% quiz.quiz_time %> นาที.</li>
						 					</ul>
						 					<a class="button is-info is-outlined" ng-class="{'is-disabled':panel.compareFromNow(quiz.end,'>') || quiz.isAnswered }" href="{{url('/Student/answerQuiz')}}/<%quiz.id%>" target="_blank">
						 						<i class="fa fa-check"> ทำแบบทดสอบ</i>
						 					</a>
								 	</div>
					 			</div>
						 	</div>
						</div>
					</div>
				</article>
			</div>
		</div>
		<!-- working quiz -->
		<div class="tile is-parent" ng-show="panel.isSelected(6)">
			<div class="tile is-child box">
				<article class="media">
					<div class="media-content" ng-show="loading">
						<loading></loading>
					</div>
					<div class="media-content" ng-show="!loading">
						<div class="columns" ng-show="allQuizzes.length">
							<div class="column is-5">
								<p class="control has-icon">
									<input type="text" class="input is-outlined is-info" ng-model="searchquiz.name" placeholder="ค้นหาแบบทดสอบ"><br/>
									<i class="fa fa-search"></i>
								</p>
							</div>
						</div>
						<div class="notification is-danger" ng-show="!allQuizzes.length && !loading">
								ไม่พบแบบทดสอบ
						</div>
						<div class="content" ng-repeat="subjects in allQuizzes">
						 	<div class="box" ng-repeat="quiz in subjects.quiz | filter:searchquiz:strict | orderBy:'-created_at'" ng-show="panel.isActive(quiz.end)">
						 		<div class="columns">
						 			<div class="column is-10">
						 				<strong> <% quiz.name %> </strong>
						 				<span class="tag is-info is-small">
								 			<i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i>
								 		</span>
						 				<span class="tag is-small" ng-class="panel.compareFromNow(quiz.end,'>')?'is-danger':'is-success'"><%panel.compareFromNow(quiz.end,'>')?'สิ้นสุดแล้ว':'กำลังทำงาน'%></span>
						 				<span class="tag is-small is-dark" ng-show="quiz.isAnswered"><i class="fa fa-check"></i> &nbsp; ทำแล้ว : <% quiz.points %>/<% quiz.quiz_q_a.length %> คะแนน</span>
							 		</div>
						 		</div>
					 			<div class="columns">
					 				<div class="column">
					 						<ul>
						 						<li>วิชา : <% subjects.name %></li>
						 						<li>วันที่เริ่มต้น : <% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>วันที่สิ้นสุด : <% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>จำนวน : <% quiz.quiz_q_a.length %> ข้อ.</li>
						 						<li>เวลา : <% quiz.quiz_time %> นาที.</li>
						 					</ul>
						 					<a class="button is-info is-outlined" ng-class="{'is-disabled':panel.compareFromNow(quiz.end,'>') || quiz.isAnswered }" href="{{url('/Student/answerQuiz')}}/<%quiz.id%>" target="_blank">
						 						<i class="fa fa-check"> ทำแบบทดสอบ</i>
						 					</a>
								 	</div>
					 			</div>
						 	</div>
						</div>
					</div>
				</article>
			</div>
		</div>
		<!-- ended quiz -->
		<div class="tile is-parent" ng-show="panel.isSelected(7)">
			<div class="tile is-child box">
				<article class="media">
					<div class="media-content" ng-show="loading">
						<loading></loading>
					</div>
					<div class="media-content" ng-show="!loading">
						<div class="columns" ng-show="allQuizzes.length">
							<div class="column is-5">
								<p class="control has-icon">
									<input type="text" class="input is-outlined is-info" ng-model="searchquiz.name" placeholder="ค้นหาแบบทดสอบ"><br/>
									<i class="fa fa-search"></i>
								</p>
							</div>
						</div>
						<div class="notification is-danger" ng-show="!allQuizzes.length && !loading">
								ไม่พบแบบทดสอบ
						</div>
						<div class="content" ng-repeat="subjects in allQuizzes">
						 	<div class="box" ng-repeat="quiz in subjects.quiz | filter:searchquiz:strict | orderBy:'-created_at'" ng-show="!panel.isActive(quiz.end)">
						 		<div class="columns">
						 			<div class="column is-10">
						 				<strong> <% quiz.name %> </strong>
						 				<span class="tag is-info is-small">
								 			<i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i>
								 		</span>
						 				<span class="tag is-small" ng-class="panel.compareFromNow(quiz.end,'>')?'is-danger':'is-success'"><%panel.compareFromNow(quiz.end,'>')?'สิ้นสุดแล้ว':'กำลังทำงาน'%></span>
						 				<span class="tag is-small is-dark" ng-show="quiz.isAnswered"><i class="fa fa-check"></i> &nbsp; ทำแล้ว : <% quiz.points %>/<% quiz.quiz_q_a.length %> คะแนน</span>
							 		</div>
						 		</div>
					 			<div class="columns">
					 				<div class="column">
					 						<ul>
						 						<li>วิชา : <% subjects.name %></li>
						 						<li>วันที่เริ่มต้น : <% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>วันที่สิ้นสุด : <% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></li>
						 						<li>จำนวน : <% quiz.quiz_q_a.length %> ข้อ.</li>
						 						<li>เวลา : <% quiz.quiz_time %> นาที.</li>
						 					</ul>
						 					<a class="button is-info is-outlined" ng-class="{'is-disabled':panel.compareFromNow(quiz.end,'>') || quiz.isAnswered }" href="{{url('/Student/answerQuiz')}}/<%quiz.id%>" target="_blank">
						 						<i class="fa fa-check"> ทำแบบทดสอบ</i>
						 					</a>
								 	</div>
					 			</div>
						 	</div>
						</div>
					</div>
				</article>
			</div>
		</div>
	</div>
</div>
<br/><br/><br/><br/>
@endsection

@section('js')
<script>
		(function(){
				var app = angular.module('Student', ['timer','ngAnimate','ngLocale','angularUtils.directives.dirPagination'], function($interpolateProvider)
					{
							$interpolateProvider.startSymbol('<%');
							$interpolateProvider.endSymbol('%>');
					});

				// directive
				app.directive('loading', function ()
				{
			      return {
			        restrict: 'E',
			        replace:true,
			        template: '<div class="loading has-text-centered"><img src="{{url("/spinner.gif")}}" width="30%" /></div>',
			        link: function (scope, element, attr) {
			              scope.$watch('loading', function (val) {
			                  if (val)
			                      $(element).show();
			                  else
			                      $(element).hide();
			              });
			        }
			      }
			  })

				app.filter('range', function() {
						return function(input, total) {
								total = parseInt(total);

								for (var i = 0; i < total; i++) {
										input.push(i);
								}

								return input;
						};
				});

				app.constant("CSRF_TOKEN", '{{ csrf_token() }}')

				app.controller('PanelController',function($http,$scope,$window, CSRF_TOKEN)
				{
					$scope.currentPage = 1;
					$scope.pageSize = 10;

					this.selectTab = function(setTab)
					{
						this.tab = setTab;
						$scope.notification = false;
						var name = this.tabName(setTab)
						if(name){
							getData(name);
							getSubject();
							getallQuizzes();
						}
						
					};

					$scope.subjects = [];
					var getSubject = function()
					{
						$http.get("{{ url('/Student') }}"+'/getRegisteredSubjects')
								.then(function(response)
								{
									$scope.subjects = [];
									$scope.subjects = response.data.result;
									//console.log($scope.subjects);
								})
					}

					$scope.allQuizzes = [];
					var getallQuizzes = function()
					{
						$http.get("{{ url('/Student') }}"+'/getRegisteredSubjectsQuizzes')
								.then(function(response)
								{
									$scope.allQuizzes = response.data.result;
									$scope.QuizCount = response.data.count;
									console.log($scope.allQuizzes);
								})
					}

					this.getSubjectQuiz = function(id){
						$http.get("{{ url('/getSubjectQuizzes') }}"+'/'+id)
								.then(function(response)
								{
									$scope.subjectquiz = [];
									$scope.subject = [];
									$scope.subjectquiz = response.data.result;
									$scope.subject = response.data.subject;
									//console.log($scope.subjectquiz);
								})
					}

					var getData = function(url)
					{
						$scope.loading = true;
						$http.get("{{ url('/') }}"+'/'+url)
								.then(function(response)
								{
										$scope.datas = response.data.result;
										$scope.loading = false;
										//console.log($scope.datas);
								});
					}
					
					this.postData = function(url,data,refresh)
					{
						$http.post("{{ url('/Student') }}"+'/'+url,{data : data,csrf_token: CSRF_TOKEN})
								.then(function(response)
								{
										$scope.post_datas = response.data.result;
										$scope.notification = true;
										//console.log($scope.post_datas);
										if(refresh){
											if($scope.post_datas[0].type == 'success'){
												setTimeout(function(){
													$window.location.reload();
												}, 3000);
											}
										}
										//console.log($scope.post_datas);
								});
					}

					this.tabName = function(number)
					{
						switch(number){
							case 1: return 'Student/getSubjects'; break;
							case 2: return 'Student/getRegisteredSubjects'; break;
							case 3: return 0; break;
							case 4: return 'getQuizzes'; break;
							case 5: return 'getActiveQuizzes'; break;
							case 6: return 'getInActiveQuizzes'; break;
							case 7: return 0; break;
							case 8: return 0; break;
						}
					};

					@if(Request::is('Student/Subject'))
						this.tab = 2;
						this.selectTab(this.tab);
					@else
						this.tab = 1;
						this.selectTab(this.tab);
					@endif

					this.isSelected = function(checkTab)
					{
						return this.tab === checkTab;
					}

					this.compareFromNow = function(time,condition)
					{
						var date = new Date();
						var time = new Date(time);
						if(condition == '<')
						{
							return date < time;
						}
						else if(condition == '>')
						{
							return date > time;
						} 
					}

					this.isActive = function(end)
					{
						var date = new Date();
						return date < this.convertTime(end);
					}

					this.convertTime = function(time)
					{
						var date = new Date(time);
						//console.log(time);
						return date;
					}
					
					this.addSubject = function(id,getApi){
						var url = 'registerSubject';
						$http.post("{{ url('/Student') }}"+'/'+url,{data : id,csrf_token: CSRF_TOKEN})
								.then(function(response)
								{
										$scope.post_datas = response.data.result;
										$scope.notification = true;
								}).then(function(){
										getData(getApi);
										getSubject();
										getallQuizzes();
								});
														
					}

					this.rmSubject = function(id,getApi){
						var url = 'removeSubject';
						$http.post("{{ url('/Student') }}"+'/'+url,{data : id,csrf_token: CSRF_TOKEN})
								.then(function(response)
								{
										$scope.post_datas = response.data.result;
										$scope.notification = true;
								}).then(function(){
										getData(getApi);
										getSubject();
										getallQuizzes();
								});
					}

				});
		})();

</script>

@endsection