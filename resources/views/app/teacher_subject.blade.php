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
														<i class="icon is-small fa fa-book"></i> แบบทดสอบทั้งหมด <span class="tag is-danger is-small" ng-bind="quizcount">{{$quizcount}}</span>
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
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
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
														<td><% panel.convertTime(subject.created_at) | date:'d MMMM y HH:mm น.' %></td>
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
									<button type="button" class="button is-info is-outlined" ng-click="panel.addStuff()">เพิ่มวิชา</button>
									<button type="button" class="button is-success is-outlined" ng-click="panel.confirmStuff('newSubject');">ยืนยัน</button>
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
						<div class="columns">
								<div class="control column">
									<div class="notification is-danger" ng-show="!datas.length">ไม่พบวิชาของคุณ กรุณาสร้างวิชาก่อนที่จะเพิ่มแบบทดสอบ</div>
								</div>
							</div>
						<div class="content" ng-show="datas.length">
							<div class="columns">
								<div class="control column">
									<div class="notification is-danger" ng-show="errmsg"><% errmsg %></div>
								</div>
							</div>
							<div class="columns">
								<div class="control column">
									<input type="text" class="input" name="name" ng-model="quiz.name" placeholder="ชื่อแบบทดสอบ">
								</div>
								<div class="control column">
									<span class="select">
									  <select  ng-model="quiz.subject_id">
									    <option ng-repeat="subject in datas" value="<% subject.id %>"><% subject.name %></option>
									  </select>
									</span>
								</div>
								<div class="column is-2">
									<p class="control has-icon has-icon-right">
										<input type="number" class="input" min="1" max="6" name="level" ng-model="quiz.level" placeholder="ระดับความยาก">
										<i class="fa fa-star"></i>
									</p>
								</div>
							</div>
							<div class="columns">
								<div class="control column">
									<p><label for="" class="label">วันที่เริ่มต้น</label></p>
									<input type="date" class="input" name="start" ng-model="quiz.start">
								</div>
								<div class="control column">
									<p><label for="" class="label">เวลา</label></p>
									<input type="time" class="input" name="starttime" ng-model="quiz.starttime">
								</div>
								<div class="control column">
									<p><label for="" class="label">วันที่สิ้นสุด</label></p>
									<input type="date" class="input" name="end" ng-model="quiz.end">
								</div>
								<div class="control column">
									<p><label for="" class="label">เวลา</label></p>
									<input type="time" class="input" name="endtime" ng-model="quiz.endtime">
								</div>
							</div>
							<div class="notification" ng-repeat="question in quiz.question">
								<button class="delete" ng-click="panel.rmQuestion($index)"></button>
								<div class="columns">
									<div class="column is-10">
										<div class="control">
										<label class="label">ข้อที่ <span><% $index+1 %></span></label>
										<input type="text" class="input" name="question" ng-model="question.ask" placeholder="คำถาม">
										</div>
									</div>
								</div>
								<div class="columns" ng-repeat="choice in question.choices">
									<div class="column is-8">
										<div class="control">
										<input type="text" class="input" name="choice" ng-model="choice.text"  placeholder="ตัวเลือก">
										</div>
									</div>
									<div class="column is-2">
										<p class="control">
											<button type="button" class="button is-outlined" ng-click="panel.correctChoice($parent.$index,$index)" ng-class="{ 'is-success':choice.isCorrect }"><i class="fa fa-check"></i></button>
											<button type="button" class="button is-danger is-outlined" ng-click="panel.rmChoice($parent.$index,$index)"><i class="fa fa-times"></i></button>
									  </p>
									</div>
								</div>
								<button type="button" class="button is-danger is-outlined" ng-click="panel.addChoice($index)"><i class="fa fa-plus"></i> &nbsp; เพิ่มตัวเลือก</button>
							</div>
							<div class="columns">
								<div class="column">
									<button type="button" class="button is-info is-outlined" ng-click="panel.addQuestion()">เพิ่มคำถาม</button>
									<button type="button" class="button is-success is-outlined is-pulled-right" ng-click="panel.confirmQuiz('newQuizzes')">ยืนยัน</button>
								</div>
							</div>
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
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
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
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></td>
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
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
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
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></td>
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
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
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
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></td>
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

				app.controller('PanelController',function($http,$scope,$window, CSRF_TOKEN)
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
										$scope.quiz.subject_id = $scope.datas[0].id;
										//console.log($scope.datas);
								});
					}
					
					this.postData = function(url,data)
					{
						$http.post("{{ url('/Teacher') }}"+'/'+url,{data : data,csrf_token: CSRF_TOKEN})
								.then(function(response)
								{
										$scope.post_datas = response.data.result;
										$scope.notification = true;
										if($scope.post_datas[0].type == 'success'){
											setTimeout(function(){
												$window.location.reload();
											}, 3000);
										}
										//console.log($scope.post_datas);
								});
					}

					this.tabName = function(number)
					{
						switch(number){
							case 1: return 'getSubjects'; break;
							case 2: return 0; break;
							case 3: return 'getSubjects'; break;
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
					
					// new stuff & quiz
					$scope.stuffs = [];

					$scope.stuffs.push({
						name: "",
						subject_number: "",
					})

					this.addStuff = function()
					{
						$scope.stuffs.push({
							name: "",
							subject_number: "",
						})
						//console.log($scope.stuffs);
					}

					this.rmStuff = function(index)
					{
						$scope.stuffs.splice(index,1);
						//console.log(index);
					}

					$scope.notification = false;
					this.confirmStuff = function(url)
					{
						var tmp = [];
						for(var i in $scope.stuffs){
							if(($scope.stuffs[i].name.length > 0 && $scope.stuffs[i].subject_number.length > 0) && $scope.stuffs[i].name != '' && $scope.stuffs[i].subject_number.length != ''){
								tmp.push($scope.stuffs[i]);
							}
						}
						
						$scope.stuffs = tmp;
						//console.log($scope.stuffs);
						this.postData(url,$scope.stuffs);
					}

					$scope.subjectcount = {{ $subjectcount }};
					$scope.quizcount = {{ $quizcount }};

					$scope.quiz = [];
					$scope.quiz.name = '';
					$scope.quiz.subject_id;
					$scope.quiz.level = 1;
					$scope.quiz.start = new Date();
					$scope.quiz.starttime = new Date(1970, 0, 1, 0, 0, 0);;
					$scope.quiz.end = new Date();
					$scope.quiz.endtime = new Date(1970, 0, 1, 0, 0, 0);
					$scope.quiz.question = [];

					//console.log($scope.quiz);

					this.addQuestion = function()
					{
						$scope.quiz.question.push({
							ask: "",
							answer: -1,
							choices: [],
						})
						//console.log($scope.quiz);
					}

					this.rmQuestion = function(index)
					{
						$scope.quiz.question.splice(index,1);
						//console.log(index);
					}

					this.addChoice = function(index)
					{
						$scope.quiz.question[index].choices.push({
							text: "",
							isCorrect: false,
						})
						//console.log($scope.quiz.question[index].choices);
					}

					this.rmChoice = function(parent,index)
					{
						$scope.quiz.question[parent].choices.splice(index,1);
					}

					this.correctChoice = function(parent,index)
					{
						for(i in $scope.quiz.question[parent].choices){
							if(i != index)
								$scope.quiz.question[parent].choices[i].isCorrect = false;
						}
						$scope.quiz.question[parent].choices[index].isCorrect = true;
						$scope.quiz.question[parent].answer = index;
						//console.log($scope.quiz.question[parent].answer);
					}

					

					this.confirmQuiz = function(url)
					{
						var $tmp = {};
						$scope.errmsg = false;
						
						if($scope.quiz.name == '' || $scope.quiz.name.length == 0)
							return $scope.errmsg = 'กรุณากรอกชื่อของแบบทดสอบ';
						if($scope.quiz.question.length == 0)
							return $scope.errmsg = 'กรุณาเพิ่มคำถาม';

						$tmp.name = $scope.quiz.name;
						$tmp.subject_id = $scope.quiz.subject_id;
						$tmp.level = $scope.quiz.level;
						$tmp.start = $scope.quiz.start;
						$tmp.starttime = $scope.quiz.starttime;
						$tmp.end = $scope.quiz.end;
						$tmp.endtime = $scope.quiz.endtime;
						$tmp.question = [];
						for(i in $scope.quiz.question)
						{
							if($scope.quiz.question[i].ask == '' || $scope.quiz.question[i].ask == 0)
								return $scope.errmsg = "กรุณากรอกคำถามให้ครบถ้วน";
							if($scope.quiz.question[i].choices.length == 0)
								return $scope.errmsg = "กรุณาเพิ่มตัวเลือกให้ครบถ้วน";
							if($scope.quiz.question[i].answer == -1)
								return $scope.errmsg = "กรุณาเลือกคำตอบที่ถูกต้องให้ครบถ้วน";

							$tmp.question.push($scope.quiz.question[i]);
							var new_choices = [];
							//console.log($scope.quiz.question[i]);
							//console.log($scope.quiz.question[i]);
							for(j in $scope.quiz.question[i].choices)
							{
								if($scope.quiz.question[i].choices[j].text != '' || $scope.quiz.question[i].choices[j].length > 0){
									new_choices.push($scope.quiz.question[i].choices[j]);
								}
								else if($scope.quiz.question[i].choices[j].isCorrect){
									$tmp.question[i].answer = -1;
									return $scope.errmsg = "กรุณาเลือกคำตอบที่ถูกต้อง";
								}
							}

							$tmp.question[i].choices = new_choices;
							//console.log($tmp);
							for(j in $tmp.question[i].choices){
								if($tmp.question[i].choices[j].isCorrect)
									$tmp.question[i].answer = j;
							}
						}

						$scope.quiz = $tmp;
						//console.log($scope.quiz);
					  this.postData(url,$scope.quiz);
						
					}

				});
		})();

</script>

@endsection