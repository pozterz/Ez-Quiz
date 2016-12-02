@extends('app.template') @section('content')

<div class="container" ng-app="Profile" ng-controller="profileController as profile">
	<div class="columns">
		<div class="column is-half is-offset-one-quarter">
			<div class="card is-fullwidth" ng-hide="loading">
			  <div class="card-image">
			    <figure class="image is-4by3">
			      <img src="http://placehold.it/480x480" alt="">
			    </figure>
			  </div>
			  <div class="card-content">
			    <div class="media">
			      <div class="media-left">
			        <figure class="image is-32x32">
			          <img src="http://placehold.it/64x64" alt="Image">
			        </figure>
			      </div>
			      <div class="content media-content">
			        <p class="title is-5" style="color:#363636;"><% data.name %></p>
			        <p class="subtitle is-6" style="color:#363636;">@<% data.username %></p>
			      </div>
			    </div>
			    <div class="content">
			      <ul>
			      	<li ng-show="data.student_id.length">รหัสนักศึกษา : <% data.student_id %></li>
			      	<li>E-mail : <% data.email %></li>
			      	<li>สมัครสมาชิกเมื่อ : <% profile.convertTime(data.created_at) | date:'d MMMM y HH:mm น.'  %></li>
			      </ul>
			    </div>
			  </div>
			  <footer class="card-footer">
			    <a class="card-footer-item">แก้ไขข้อมูลส่วนตัว</a>
			  </footer>
			</div>
		</div>
	</div>
</div>

@endsection


@section('js')

<script>

	(function(){
				var app = angular.module('Profile', ['timer','ngAnimate','ngLocale','angularUtils.directives.dirPagination'], function($interpolateProvider)
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

				app.controller('profileController',function($http,$scope,$window, CSRF_TOKEN)
				{

						var getProfile = function()
						{
							$scope.loading = true;
							$http.get("{{ url('/getProfile') }}")
									.then(function(response)
									{
											$scope.data = response.data.result;
											$scope.loading = false;
											//console.log($scope.datas);
									});
						}

						this.convertTime = function(time)
						{
							var date = new Date(time);
							//console.log(time);
							return date;
						}

						getProfile();
				});
		})();

</script>

@endsection