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
									<a href="#" ng-click="panel.getSubjectQuiz(subject.id); panel.selectTab(3);"> <% subject.name %></a>
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
							<i class="icon is-small fa fa-book"></i> แบบทดสอบทั้งหมด <span class="tag is-danger is-small" ng-bind="quizcount"></span>
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
							<loading></loading>
							<div class="columns">
								<div class="column">
									<p class="control has-icon is-pulled-right">
										<input type="text" name="search" class="input is-info is-outlined" ng-model="search.name">
										<i class="fa fa-search"></i>
									</p>
								</div>
							</div>
							<br/>
							<div class="notification is-info" ng-show="notification" ng-if="post_datas.length">
								<button class="delete" ng-click="notification = false"></button>
									<p ng-repeat="message in post_datas">
										<% message.message %>
									</p>
							</div>
							<div class="columns">
								<div class="column">
						 			<div class="box" dir-paginate="subject in datas | filter:search:strict | itemsPerPage: pageSize" current-page="currentPage" >
						 				<h4>
						 					<strong><% subject.subject_number %> : <% subject.name %></strong>
						 				</h4>
						 				<h6>
						 					เจ้าของวิชา : <% subject.user.name %> 
						 				</h6>
						 				<button ng-if="!subject.isRegistered" ng-click="panel.addSubject(subject.id)" type="button" class="button is-success is-outlined"><i class="fa fa-plus"></i> &nbsp; ลงทะเบียน</button>
						 				<button ng-if="subject.isRegistered" ng-click="panel.rmSubject(subject.id)" type="button" class="button is-danger is-outlined"><i class="fa fa-times"></i> &nbsp; ออกจากวิชานี้</button>
						 			</div>
						 		</div>
						 </div>
						</div>
						<br/>
						<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="../dirPagination.tpl.html"></dir-pagination-controls>
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
							<loading></loading>
						 <div class="box" ng-repeat="subject in subjects">
						 	<% subject.name %>
						 </div>
						</div>
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
							<div class="notification is-danger" ng-show="!subjectquiz.length">
								ไม่พบแบบทดสอบของวิชานี้
							</div>
						 	<div class="box" ng-repeat="quiz in subjectquiz">
						 		<div class="columns">
						 			<div class="column is-10">
						 				<strong> <% quiz.name %> </strong>
						 				<span class="tag is-info is-small">
								 			<i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i>
								 		</span>
						 				<span class="tag is-small" ng-class="panel.compareFromNow(quiz.end,'>')?'is-danger':'is-success'"><%panel.compareFromNow(quiz.end,'>')?'สิ้นสุดแล้ว':'กำลังทำงาน'%></span>
						 				 <br/>
							 		</div>
						 		</div>
					 			<div class="columns">
					 				<div class="column">
								 			วันที่เริ่มต้น : <% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %> <br/>
								 			วันที่สิ้นสุด : <% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %> 
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
				var app = angular.module('Student', ['ngLocale','angularUtils.directives.dirPagination'], function($interpolateProvider)
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
							this.getData(name);
							this.getSubject();
							//this.getSubjectCount();
						}
						
					};

					$scope.subjects = [];
					this.getSubject = function()
					{
						$http.get("{{ url('/Student') }}"+'/getRegisteredSubjects')
								.then(function(response)
								{
									$scope.subjects = response.data.result;
									//console.log($scope.subjects);
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
									console.log($scope.subjectquiz);
								})
					}

					this.getSubjectCount = function()
					{
						$http.get("{{ url('/Student') }}"+'/getSubjectCount')
								.then(function(response)
								{
									$scope.subjectcount = response.data.result;
								})
					}

					this.getData = function(url)
					{
						$scope.loading = true;
						$http.get("{{ url('/') }}"+'/'+url)
								.then(function(response)
								{
										$scope.datas = response.data.result;
										$scope.loading = false;
										console.log($scope.datas);
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
							case 2: return 0; break;
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

					this.convertTime = function(time)
					{
						var date = new Date(time);
						return date;
					}
					
					this.addSubject = function(id){
						var url = 'registerSubject';
						this.postData(url,id,false);
						this.selectTab(1);
					}

					this.rmSubject = function(id){
						var url = 'removeSubject';
						this.postData(url,id,false);
						this.selectTab(1);
					}

				});
		})();

</script>

@endsection