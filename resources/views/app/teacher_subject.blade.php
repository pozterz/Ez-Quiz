@extends('app.template') @section('content')
<div class="container" ng-app="Teacher">
		<div class="tile is-ancestor" ng-controller="PanelController as panel">
				<div class="tile is-vertical is-3 is-parent">
						<div class="tile is-child box">
								<aside class="menu">
										<p class="menu-label">
												วิชา
										</p>
										<ul class="menu-list">
												<li>
													<a href="#" ng-click="panel.selectTab(2)" ng-class="{ 'is-active':panel.isSelected(2) }">
														<i class="icon is-small fa fa-plus"></i> เพิ่มวิชา
													</a>
												</li>
												<li>
													<a href="#" ng-click="panel.selectTab(1);" ng-class="{ 'is-active':panel.isSelected(1) }">
														<i class="icon is-small fa fa-clone"></i> วิชาทั้งหมด <span class="tag is-danger is-small" ng-bind="subjectcount">{{$subjectcount}}</span>
													</a>
												</li>
										</ul>
										<p class="menu-label">
												แบบทดสอบ
										</p>
										<ul class="menu-list">
												<li>
													<a href="#" ng-click="panel.selectTab(3)" ng-class="{ 'is-active':panel.isSelected(3) }">
														<i class="icon is-small fa fa-plus"></i> เพิ่มแบบทดสอบ
													</a>
												</li>
												<li>
														<a href="#" ng-click="panel.selectTab(4);" ng-class="{ 'is-active':panel.isSelected(4) }">
														<i class="icon is-small fa fa-book"></i> แบบทดสอบทั้งหมด
														</a>
														<ul>
																<li>
																	<a href="#" ng-click="panel.selectTab(5)" ng-class="{ 'is-active':panel.isSelected(5) }">กำลังทำงาน</a>
																</li>
																<li>
																	<a href="#" ng-click="panel.selectTab(6)" ng-class="{ 'is-active':panel.isSelected(6) }">สิ้นสุดแล้ว</a>
																</li>
														</ul>
												</li>
										</ul>
										<p class="menu-label">
												จัดการ
										</p>
										<ul class="menu-list">
												<li>
													<a href="#" ng-click="panel.selectTab(7)" ng-class="{ 'is-active':panel.isSelected(7) }">
														<i class="icon is-small fa fa-user-plus"></i> เพิ่มผู้เรียน
														</a>
												</li>
												<li>
													<a href="#" ng-click="panel.selectTab(8)" ng-class="{ 'is-active':panel.isSelected(8) }">
														<i class="icon is-small fa fa-user-times"></i> ลบผู้เรียน
													</a>
												</li>
										</ul>
								</aside>
						</div>
				</div>
				<!-- all subject -->
				<div class="tile is-parent" ng-show="panel.isSelected(1)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification is-primary" ng-show="!datas.length && !loading">
											ไม่พบวิชาของคุณในฐานข้อมูล
										</div>
										<div class="table" ng-hide="loading">
											<table class="table" ng-show="datas.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('id')">
															# <i class="fa fa-sort" ng-show="sort === 'id'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject_number')">
															รหัสวิชา <i class="fa fa-sort" ng-show="sort === 'subject_number'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('created_at')">
															เวลาที่สร้าง <i class="fa fa-sort" ng-show="sort === 'created_at'" ng-class="{reverse: reverse}"></i>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="subject in datas | filter:search:strict | orderBy:sort:reverse">
														<td><% $index+1 %></td>
														<td><% subject.name  %></td>
														<td><% subject.subject_number %></td>
														<td><% panel.convertTime(subject.created_at) | date:'d MMMM y hh:mm น.' %></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- add subject -->
				<div class="tile is-parent" ng-show="panel.isSelected(2)">
						<div class="tile is-child box">
								<div class="content">
									<div ng-repeat="stuff in stuffs" class="control columns">
											<div class="column is-one-quarter">
												<input type="text" class="input is-primary" placeholder="รหัสวิชา" ng-model="stuff.subject_number">
											</div>
											<div class="column is-two-thirds">
												<input type="text" class="input is-primary" placeholder="ชื่อวิชา" ng-model="stuff.name">
											</div>
											<div class="column">
												<button type="button" class="button is-danger is-outlined" ng-click="panel.rmStuff($index)"><i class="fa fa-times"></i></button>
											</div>
									</div>
									<button type="button" class="button is-info is-outlined" ng-click="panel.addStuff()">Add</button>
									<button type="button" class="button is-success is-outlined" ng-click="panel.confirmStuff('newSubject');">Confirm</button>
									<br/><br/>
									<div class="notification is-primary" ng-show="notification" ng-if="post_datas.length">
										 <button class="delete" ng-click="notification = false"></button>
										 <p ng-repeat="message in post_datas">
										 	 <% message.message %>
										 </p>
									</div>
								</div>
						</div>
				</div>
				<!-- add quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(3)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<p>xx</p>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- all quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(4)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification is-primary" ng-show="!datas.length && !loading">
											ไม่พบคำถามในฐานข้อมูล
										</div>
										<div class="table" ng-hide="loading">
											<table class="table" ng-show="datas.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('id')">
															# <i class="fa fa-sort" ng-show="sort === 'id'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject.name')">
															รหัสวิชา / ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('end')">
															เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('level')">	
															ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="quiz in datas | filter:search:strict | orderBy:sort:reverse">
														<td><% $index+1 %></td>
														<td><% quiz.name  %></td>
														<td><% quiz.subject.subject_number %> : <% quiz.subject.name  %></td>
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y hh:mm น.' %></td>
														<td><% quiz.level  %></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- active quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(5)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification is-primary" ng-show="!datas.length && !loading">
											ไม่พบคำถามที่กำลังทำงาน ในฐานข้อมูล
										</div>
										<div class="table" ng-hide="loading">
											<table class="table" ng-show="datas.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('id')">
															# <i class="fa fa-sort" ng-show="sort === 'id'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject.name')">
															รหัสวิชา / ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('end')">
															เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('level')">	
															ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="quiz in datas | filter:search:strict | orderBy:sort:reverse">
														<td><% $index+1 %></td>
														<td><% quiz.name  %></td>
														<td><% quiz.subject.subject_number %> : <% quiz.subject.name  %></td>
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y hh:mm น.' %></td>
														<td><% quiz.level  %></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- ended quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(6)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification is-primary" ng-show="!datas.length && !loading">
											ไม่พบคำถามที่สิ้นสุดการทำงานแล้ว ในฐานข้อมูล
										</div>
										<div class="table" ng-hide="loading">
											<table class="table" ng-show="datas.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('id')">
															# <i class="fa fa-sort" ng-show="sort === 'id'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject.name')">
															รหัสวิชา / ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('end')">
															เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('level')">	
															ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="quiz in datas | filter:search:strict | orderBy:sort:reverse">
														<td><% $index+1 %></td>
														<td><% quiz.name  %></td>
														<td><% quiz.subject.subject_number %> : <% quiz.subject.name  %></td>
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y hh:mm น.' %></td>
														<td><% quiz.level  %></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- add member -->
				<div class="tile is-parent" ng-show="panel.isSelected(7)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<p>xxxxxxxxx</p>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- remove member -->
				<div class="tile is-parent" ng-show="panel.isSelected(8)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<p>xxxxxxxxxx</p>
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
				var app = angular.module('Teacher', ['ngLocale','angularUtils.directives.dirPagination'], function($interpolateProvider)
					{
							$interpolateProvider.startSymbol('<%');
							$interpolateProvider.endSymbol('%>');
					});

				// directive
				app.directive('loading', function () {
			      return {
			        restrict: 'E',
			        replace:true,
			        template: '<div class="loading has-text-centered"><img src="{{url("/progress.gif")}}" width="30%" /></div>',
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

				//
				app.constant("CSRF_TOKEN", '{{ csrf_token() }}')

				app.controller('PanelController',function($http,$scope, CSRF_TOKEN)
				{

					this.selectTab = function(setTab)
					{
						this.tab = setTab;
						$scope.notification = false;
						var name = this.tabName(setTab)
						if(name){
							this.getData(name);
							//this.getSubjectCount();
						}
						
					};
					this.getSubjectCount = function()
					{
						$http.get("{{ url('/Teacher') }}"+'/getSubjectCount')
								.then(function(response)
								{
									$scope.subjectcount = response.data.result;
								})
					}

					this.getData = function(url)
					{
						$scope.loading = true;
						$http.get("{{ url('/Teacher') }}"+'/'+url)
								.then(function(response)
								{
										$scope.datas = response.data.result;
										$scope.loading = false;
										console.log($scope.datas);
								});
					}

					this.postData = function(url,data)
					{
						$http.post("{{ url('/Teacher') }}"+'/'+url,{data : data,csrf_token: CSRF_TOKEN})
								.then(function(response)
								{
										$scope.post_datas = response.data.result;
										$scope.notification = true;
										console.log($scope.post_datas);
								});
					}

					this.tabName = function(number)
					{
						switch(number){
							case 1: return 'getSubjects'; break;
							case 2: return 0; break;
							case 3: return 0; break;
							case 4: return 'getQuizzes'; break;
							case 5: return 'getActiveQuizzes'; break;
							case 6: return 'getInActiveQuizzes'; break;
							case 7: return 0; break;
							case 8: return 0; break;
						}
					};

					@if(Request::is('Teacher/Subject'))
						this.tab = 1;
						this.selectTab(this.tab);
					@else
						this.tab = 2;
						this.selectTab(this.tab);
					@endif

					this.isSelected = function(checkTab)
					{
						return this.tab === checkTab;
					}

					// Sort
						$scope.now = new Date();
						$scope.currentPage = 1;
						$scope.pageSize = 10;
						$scope.sort = 'end';
						$scope.reverse = false;

						this.sortBy = function(propertie)
						{
							$scope.reverse = ($scope.sort === propertie) ? !$scope.reverse : false;
							$scope.sort = propertie;
						}

						this.convertTime = function(time)
						{
							var date = new Date(time);
							return date;
						}
						// new stuff
						$scope.stuffs = [];
						$scope.stuffs.push({
								name: "",
								subject_number: "",
							})

						this.addStuff = function(){
							$scope.stuffs.push({
								name: "",
								subject_number: "",
							})
							//console.log($scope.stuffs);
						}

						this.rmStuff = function(index){
							$scope.stuffs.splice(index,1);
							//console.log(index);
						}

						$scope.notification = false;
						this.confirmStuff = function(url){
							for(i in $scope.stuffs){
								if($scope.stuffs[i].name == '' && $scope.stuffs[i].subject_number == ''){
									$scope.stuffs.splice(i,1);
								}
							}
							this.postData(url,$scope.stuffs);
						}

						$scope.subjectcount = {{ $subjectcount }};


					});
		})();

</script>

@endsection
