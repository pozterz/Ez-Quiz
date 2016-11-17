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
								<div class="notification is-danger" ng-show="!quizzes.length && !loading">
									ไม่พบคำถามในฐานข้อมูล
								</div>
								<div id="quiz" class="tile" ng-repeat="quiz in quizzes" ng-if="$index % 3 == 0" ng-hide="loading">
										<div class="tile is-parent" ng-repeat="quiz in quizzes.slice($index, ($index+3 > quizzes.length ? quizzes.length : $index+3)) | filter:search:strict">
												<div class="tile is-child hovereffect">
														<article class="notification is-bold" ng-class="QuizCtrl.diffcolor(quiz.level)">
																<p class="title is-4 ">
																		<% quiz.name %>
																</p>
																<p class="subtitle is-6">
																		<% quiz.subject.subject_number %> : <% quiz.subject.name %> | โดย : <% quiz.subject.user.name %>
																		<br/>
																		<br/> เวลาสิ้นสุด :
																		<% QuizCtrl.convertTime(quiz.end) | date:'EEEEที่ d MMMM y HH:mm น.' %>
																		<br/>
																		<br/> ระดับ : <span class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></span>
																</p>
																<p>
																		@if(!Auth::guest() && Auth::user()->type == 'student')
																		<a href="#" alt="ทำแบบทดสอบ"><i class="fa fa-edit icon is-medium"></i></a> @else
																		<a href="#" alt="ข้อมูลวิชา"><i class="fa fa-info-circle icon is-medium"></i></a> @endif
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
		var app = angular.module('EzQuiz', ['ngLocale'], function($interpolateProvider) {
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

				this.convertTime = function(time) {
						var date = new Date(time);
						return date;
				}

				this.getQuizzes = function() {
						$scope.loading = true;
						$http.get("{{ url('/getActiveQuizzes') }}")
								.then(function(response) {
										$scope.loading = false;
										$scope.quizzes = response.data.result;
								});
				}

				this.getQuizzes();
		});

})();


</script>
@endsection
