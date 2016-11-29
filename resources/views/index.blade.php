@extends('layouts.template') @section('content')
<section class="section" ng-app="EzQuiz" ng-controller="QuizController as QuizCtrl">
		<div class="columns">
				<div class="column">
						<p class="control has-icon has-icon-right is-pulled-right">
								<input type="text" class="is-medium input is-info" ng-model="search.name" placeholder="Search">
								<i class="fa fa-search icon"></i>
						</p>
				</div>
				<br/>
				<br/>
		</div>
		</div>
		<div class="box">
				<div class="tile is-ancestor">
						<div class="tile is-vertical is-12">
								<loading></loading>
								<div class="notification fade is-danger" ng-show="!quizzes.length && !loading">
									ไม่พบคำถามที่กำลังทำงานอยู่
								</div>
								<nav class="level">
								  <div class="level-item has-text-centered">
								    <p class="heading">ผู้ใช้</p>
								    <p class="title">{{ $users }}</p>
								  </div>
								  <div class="level-item has-text-centered">
								    <p class="heading">วิชา</p>
								    <p class="title">{{ $subjects }}</p>
								  </div>
								  <div class="level-item has-text-centered">
								    <p class="heading">แบบทดสอบ</p>
								    <p class="title">{{ $quizzes }}</p>
								  </div>
								  <div class="level-item has-text-centered">
								    <p class="heading">คำตอบ</p>
								    <p class="title">{{ $answer }}</p>
								  </div>
								</nav><hr/>
								<div id="quiz" class="tile" ng-repeat="quiz in quizzes" ng-if="$index % 3 == 0" ng-hide="loading">
										<div class="tile is-parent" ng-repeat="quiz in quizzes.slice($index, ($index+3 > quizzes.length ? quizzes.length : $index+3)) | filter:search:strict">
												<div class="tile is-child hovereffect">
														<article class="notification is-bold" ng-class="QuizCtrl.diffcolor(quiz.level)">
																<p class="title is-4 ">
																		<% quiz.name %>
																</p>
																
																<ul>
																	<li><% quiz.subject.subject_number %> : <% quiz.subject.name %></li>
																	<li>โดย : <% quiz.subject.user.name %></li>
																	<li>วันที่สิ้นสุด :
																<% QuizCtrl.convertTime(quiz.end) | date:'EEEEที่ d MMMM y HH:mm น.' %></li>
																	<li>
																			<timer countdown="QuizCtrl.countd(quiz.end)"  max-time-unit="'day'" interval="1000">
																				เหลือเวลา : <% days %> วัน, <%hours %> ชั่วโมง <% mminutes %> นาที <% sseconds %> วินาที
																			</timer>
																	</li>
																</ul>
																	 
																ระดับ : <span class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></span>
																
																<p>
																	<br/>
																		@if(!Auth::guest() && Auth::user()->type == 'student')
																			<a class="button is-outlined" ng-class="QuizCtrl.diffcolor(quiz.level)" alt="ลงทะเบียน" ng-show="!quiz.isRegist" href="{{url('/Student/addSubject')}}">
																				<i class="fa fa-plus fa-2x"></i> &nbsp; ลงทะเบียน
																			</a> 
																			<a class="button is-outlined"  ng-class="QuizCtrl.diffcolor(quiz.level)" alt="ทำแบบทดสอบ" ng-show="quiz.isRegist" href="{{url('/Student/answerQuiz')}}/<%quiz.id%>" target="_blank">
																				<i class="fa fa-edit fa-2x"></i>  &nbsp; ทำแบบทดสอบ
																			</a> 
																		@else
																			<a class="button is-outlined" ng-class="QuizCtrl.diffcolor(quiz.level)" alt="ทำแบบทดสอบ" ng-show="QuizCtrl.isOwn(quiz.subject.user_id)" href="{{url('/Teacher/Subject?subject=')}}<%quiz.subject.id%>" target="_blank">
																				<i class="fa fa-info-circle fa-2x"></i>  &nbsp; ข้อมูลวิชา
																			</a> 
																		@endif
																</p>
														</article>
												</div>
										</div>
								</div>
						</div>
				</div>
		</div>
</section>
@endsection @section('js')
<script>
/* angularjs */
(function() {
		var app = angular.module('EzQuiz', ['timer','ngAnimate','ngLocale'], function($interpolateProvider) {
				$interpolateProvider.startSymbol('<%');
				$interpolateProvider.endSymbol('%>');
		});
		// directive
				app.directive('loading', function () {
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
		app.controller('QuizController', function($scope, $http) {

				$scope.now = new Date();

				this.diffcolor = function(level) {
						switch (level) {
								case 1:
										return 'is-teal';
										break;
								case 2:
										return 'is-light-blue';
										break;
								case 3:
										return 'is-green';
										break;
								case 4:
										return 'is-amber';
										break;
								case 5:
										return 'is-deep-orange';
										break;
								case 6:
										return 'is-red';
										break;
						}
				}
				@if(!Auth::guest())
				this.isOwn = function(quizid)
				{

					var userid = {{ Auth::user()->id }}
					return userid === quizid;
				}
				@endif

				this.convertTime = function(time) {
						var date = new Date(time);
						return date;
				}

				this.countd = function(end){
					var date = new Date();
					var end = new Date(end);
					return ((end.getTime() / 1000) - (date.getTime() / 1000));
				}

				this.getQuizzes = function() {
						$scope.loading = true;
						$http.get("{{ url('/getActiveQuizzes') }}")
								.then(function(response) {
										$scope.loading = false;
										$scope.quizzes = response.data.result;
										console.log($scope.quizzes)
								});
				}

				this.getQuizzes();
		});

})();


</script>
@endsection
