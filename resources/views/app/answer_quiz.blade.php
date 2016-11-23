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
						<div class="columns">
							<div class="column has-text-centered">
								<a href="{{url('index')}}" class="button is-danger is-outlined ">พาฉันกลับไป</a>
							</div>
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
				 						<li>จำนวน : <% Questions[0].quiz_qa.length %> ข้อ</li>
				 						<li>เวลา : <% Questions[0].quiz_time %> นาที.</li>
					 				</ul>
								</div>
							</div>
							<div class="columns">
								<div class="column">
									<button type="button" class="button is-info is-outlined" ng-class="{ 'is-disabled':start }" ng-click="start = 1; startTimer();" ng-hide="result.length">
										<i class="fa fa-check"></i> &nbsp; เริ่มทำแบบทดสอบ
									</button>
								</div>
								<div class="column is-offset-4">
									<span class="is-pulled-right">
										<timer countdown="Questions[0].quiz_time*60" autostart="false" max-time-unit="'minute'" interval="1000" >
											<h3><strong><% mminutes %> นาที <% sseconds %> วินาที</strong></h3>
										</timer>
									</span>
									<br/>
								</div>
							</div>
							<div class="columns" ng-show="!loading">
								<div class="column">
									<div class="notification" dir-paginate="Question in Questions[0].quiz_qa | filter:search:strict | itemsPerPage: pageSize" current-page="currentPage" pagination-id="AnswerQuiz" ng-show="start">
										<h3>
											คำถามที่ <% currentPage %> : <% Question.question %>
										</h3>
										<div style="padding-left: 40px;word-break: break-word;">
										<div ng-repeat="choice in Question.choice" class="choices">
											<input type="radio" id="radio-<%choice.id%>" name="ans" ng-click="answerQuiz.Answer(Question.id,choice.id)" ng-checked="answerQuiz.isChecked(currentPage-1,choice.id)">
											<label for="radio-<%choice.id%>">
												<span class="radio"><% choice.text %></span>
											</label>
										</div>
										</div>
									</div>
								</div>
							</div>
							<div class="columns" ng-show="!loading && result.result.length">
								<div class="column has-text-centered">
									<div class="content" style="font-size: 1.4em;">
										<div class="notification is-success" ng-show="result.type">
											คะแนนที่ได้ : <% result.result[0].points %> คะแนน.<br/>
											เวลาที่ใช้ไป : <% result.result[0].minutes %> นาที <% result.result[0].seconds %> วินาที.
										</div>
										<div class="notification is-danger" ng-show="!result.type">
											<% result.result %>
										</div>
										<a href="{{url('index')}}" class="button is-success is-outlined ">กลับหน้าแรก</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</article>
				<br/>
				<div class="columns" ng-show="!loading && start">
						<dir-pagination-controls class="column" boundary-links="true" on-page-change="pageChangeHandler(newPageNumber)" template-url="{{ url('/dirPaginationAnswer.tpl.html') }}" pagination-id="AnswerQuiz"></dir-pagination-controls>
				</div>
				<div class="columns is-mobile" ng-show="start">
					<div class="column is-half is-offset-one-quarter has-text-centered">
						<button type="button" class="button is-success is-outlined" ng-class="{ 'is-disabled':!start }" ng-click="start = 1; sendAnswer();">
							<i class="fa fa-check"></i> &nbsp; ยืนยันการส่งคำตอบ
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

    $scope.sendAnswer = function (){
       $scope.$broadcast('timer-stop');
       $scope.timerRunning = false;
    };

    // time's up
    $scope.$on('timer-stopped', function (event, data){
    	if(data.minutes == 0 && data.seconds == 0 )
    		sendData(data,true);
    	else sendData(data,false);
    });

    var sendData = function(time,apply){
    	$scope.loading = true;
    	$scope.start = false;
    	answer.minutes = time.minutes;
			answer.seconds = time.seconds;
    	console.log(answer);
    	var url = "sendAnswer";
    	$http.post("{{ url('/Student') }}"+'/'+url,{data : answer,csrf_token: CSRF_TOKEN})
				.then(function(response)
				{
    				$scope.loading = false;
						$scope.result = response.data;
						console.log($scope.result);
				});

    	if(apply)
    		return $scope.$apply();

    	console.log(answer);
    }

    var answer = {};
		answer.quiz_id = -1;
		answer.minutes = 0;
		answer.seconds = 0;
		answer.data = [];

    this.Answer = function(question,ans){
    	for(var i = 0; i < answer.data.length; i++){
    		if(answer.data[i].question_id == question){
    			answer.data[i].answer = ans;
    		}
    	}
    	
    	//console.log(answer);
    }

   

		var getData = function()
		{
			$scope.loading = true;
			$http.get("{{ url('/getQuiz') }}"+'/'+{{$id}})
					.then(function(response)
					{
						$scope.Questions = response.data.result;
						answer.quiz_id = $scope.Questions[0].id;
						for(var i = 0; i < $scope.Questions[0].quiz_qa.length; i++){
							answer.data.push({
				    		'question_id':$scope.Questions[0].quiz_qa[i].id,
				    		'answer': -1,
				    	});
						}
						$scope.loading = false;
						//console.log($scope.Questions[0]);
					});
		}
		this.isChecked = function(index,choice){
    		return answer.data[index].answer == choice;
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