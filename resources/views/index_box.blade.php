
@extends('layouts.template')

@section('content')
 <section class="section" ng-app="EzQuiz">
	<div class="container">
		<div class="columns">
			<div class="column is-8 is-offset-2">
				<div class="box" ng-controller="QuizController as QuizCtrl">
					<div class="notification is-primary" ng-hide="quizzes.length">
					  ไม่พบคำถามในฐานข้อมูล
					</div>
					<div class="table">
						<table class="table" ng-show="quizzes.length" >
							<caption>
								<p class="title is-1">Available Quiz 
									<i class="fa fa-question-circle"></i>
								</p>
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
									<th ng-click="QuizCtrl.sortBy('id')">
										ลำดับ <i class="fa fa-sort" ng-show="sort === 'id'" ng-class="{reverse: reverse}"></i>
									</th>
									<th ng-click="QuizCtrl.sortBy('name')">
										ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
									</th>
									<th ng-click="QuizCtrl.sortBy('subject.name')">
										รหัสวิชา / ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
									</th>
									<th ng-click="QuizCtrl.sortBy('end')">
										เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
									</th>
									<th ng-click="QuizCtrl.sortBy('level')">	
										ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
									</th>
									@if(!Auth::guest() && Auth::user()->type == 'student')
									<th>	
										ทำแบบทดสอบ
									</th>
									@endif
								</tr>
							</thead>
							<tbody>
								<tr dir-paginate="quiz in quizzes | filter:search:strict | orderBy:sort:reverse | itemsPerPage: pageSize" current-page="currentPage">
									<td><% quiz.id %></td>
									<td><% quiz.name  %></td>
									<td><% quiz.subject.subject_number %> : <% quiz.subject.name  %></td>
									<td><% QuizCtrl.convertTime(quiz.end) | date:'dd/MM/yyyy' %></td>
									<td><% quiz.level  %></td>
									@if(!Auth::guest() && Auth::user()->type == 'student')
										<td><a><i class="fa fa-edit icon is-medium"></i></a></td>
									@endif
								</tr>
							</tbody>
						</table>
						<dir-pagination-controls boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="dirPagination.tpl.html"></dir-pagination-controls>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@section('js')
	<script>
		/* responsive table */
		var headertext = [];
		var headers = document.querySelectorAll("thead");
		var tablebody = document.querySelectorAll("tbody");

		for (var i = 0; i < headers.length; i++) {
			headertext[i]=[];
			for (var j = 0, headrow; headrow = headers[i].rows[0].cells[j]; j++) {
			  var current = headrow;
			  headertext[i].push(current.textContent);
			  }
		} 

		for (var h = 0, tbody; tbody = tablebody[h]; h++) {
			for (var i = 0, row; row = tbody.rows[i]; i++) {
			  for (var j = 0, col; col = row.cells[j]; j++) {
			    col.setAttribute("data-th", headertext[h][j]);
			  } 
			}
		}

		/* angularjs */
	(function(){
		var app = angular.module('EzQuiz',['angularUtils.directives.dirPagination'], function($interpolateProvider) {
					$interpolateProvider.startSymbol('<%');
					$interpolateProvider.endSymbol('%>');
			});
		
		app.controller('QuizController',function($scope,$http){

			$scope.now = new Date();
			$scope.currentPage = 1;
			$scope.pageSize = 10;
			$scope.sort = 'end';
			$scope.reverse = false;

			this.sortBy = function(propertie){
				$scope.reverse = ($scope.sort === propertie) ? !$scope.reverse : false;
				$scope.sort = propertie;
			}

			this.convertTime = function(time){
				var date = new Date(time);
				return date;
			}

			this.getQuizzes = function(){
				$http.get("{{ url('/getActiveQuizzes') }}")
				.then(function(response) {
					$scope.quizzes = response.data.result;
				});
			}
			
			this.getQuizzes();
		});
		
	})();
	</script>
@endsection
