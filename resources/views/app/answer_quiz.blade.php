@extends('app.template') @section('content')
<div class="container" ng-app="Student">
	<div class="columns" ng-controller="answerQuizController as answerQuiz">
		<div class="column box">
			<article class="media">
				<div class="media-content">
					<div class="content">
						@if($errr)
						<div class="notification is-danger">
							<button type="button" class="delete"></button>
							{{ $errr }}
						</div>
					@else
							<div class="media-content" ng-show="loading">
								<loading></loading>
							</div>
						<div class="columns" ng-show="!loading">
							<div class="column">
									<h3><strong>แบบทดสอบ : <% Questions[0].name %></strong></h3>
									<h6></h6>
									<ul>
				 						<li>วิชา : <% Questions[0].subject.name %></li>
				 						<li>รหัสวิชา : <% Questions[0].subject.subject_number %></li>
				 						<li>วันที่เริ่มต้น : <% answerQuiz.convertTime(Questions[0].start) | date:'d MMMM y HH:mm น.' %></li>
				 						<li>วันที่สิ้นสุด : <% answerQuiz.convertTime(Questions[0].end) | date:'d MMMM y HH:mm น.' %></li>
				 						<li>
								 				ความยาก : <i class="fa fa-star" ng-repeat="x in [] | range:Questions[0].level"></i>
				 						</li>
				 						<li>เวลา : <% Questions[0].quiz_time %> นาที.</li>
					 				</ul>
									<button type="button" class="button is-info is-outlined" ng-class="{ 'is-disabled':start }" ng-click="start = 1; startTimer();">
										<i class="fa fa-check"></i> &nbsp; เริ่มทำแบบทดสอบ
									</button>
									<span class="is-pulled-right">
										<timer countdown="Questions[0].quiz_time*60" autostart="false" max-time-unit="'minute'" interval="1000">
											<% mminutes %> นาที <% sseconds %> วินาที
										</timer>
									</span>
								</div>
							</div>
							<div class="columns">
								<div class="column">
									<div class="notification" dir-paginate="Question in Questions[0].quiz_qa | filter:search:strict | itemsPerPage: pageSize" current-page="currentPage" pagination-id="AnswerQuiz" ng-show="start">
										<h3>
											คำถามที่ <% currentPage %> : <% Question.question %>
										</h3>
										<span ng-repeat="choice in Question.choice">
											<label>
												<input type="radio" >
												<% choice.text %><br/>
											</label>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</article>
				<br/>
				<div class="columns" ng-show="!loading && start">
						<dir-pagination-controls class="column" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{ url('/dirPagination.tpl.html') }}" pagination-id="AnswerQuiz"></dir-pagination-controls>
				</div>
				<div class="columns is-mobile" ng-show="start">
					<div class="column is-half is-offset-one-quarter has-text-centered">
						<button type="button" class="button is-success is-outlined" ng-class="{ 'is-disabled':!start }" ng-click="start = 1; stopTimer();">
							<i class="fa fa-check"></i> &nbsp; ส่งคำตอบ
						</button>
					</div>
				</div>
				@endif
		</div>
	</div>
</div>
@endsection

@if(!$errr)
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
	app.controller('answerQuizController',function($http,$scope,$window, CSRF_TOKEN)
	{
		$scope.currentPage = 1;
		$scope.pageSize = 1;
		$scope.start = false;
		$scope.Questions = [];

		$scope.startTimer = function (){
      $scope.$broadcast('timer-start');
      $scope.timerRunning = true;
    };

    $scope.stopTimer = function (){
        $scope.$broadcast('timer-stop');
        $scope.timerRunning = false;
    };

    $scope.$on('timer-stopped', function (event, data){
      console.log('Timer Stopped - data = ', data);
    });

		var getData = function()
		{
			$scope.loading = true;
			$http.get("{{ url('/getQuiz') }}"+'/'+{{$id}})
					.then(function(response)
					{
						$scope.Questions = response.data.result;
						$scope.loading = false;
						console.log($scope.Questions);
					});
		}

		this.convertTime = function(time)
		{
			var date = new Date(time);
			//console.log(time);
			return date;
		}

		getData();

	});

})();

</script>
	@endsection
@endif