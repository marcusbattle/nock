angular.module('social', ['ngRoute'] )
	.config( function( $routeProvider, $locationProvider) {
		
		$locationProvider.html5Mode( true );

		$routeProvider
			.when('/', {
				templateUrl: social.views + '/status-home.php',
				controller: 'Home'
			})
			.when('/status/:ID', {
				templateUrl: social.views + '/status-single.php',
				controller: 'Single'
			})
			.when('/groups', {
				templateUrl: social.views + '/groups-home.php',
				controller: 'Groups'
			})
			.when('/groups/:ID', {
				templateUrl: social.views + '/single-group.php',
				controller: 'SingleGroup'
			});

	})
    .controller( 'Home', function( $scope, $http, $routeParams ) {
    
    	$http.get( 'wp-json/social-api/v1/statuses/' ).success( function( response ) {
			$scope.statuses = response;
		});
        
    })
    .controller( 'Single', function( $scope, $http, $routeParams ) {
		
		$http.get( 'wp-json/social-api/v1/statuses/' + $routeParams.ID ).success( function( response ) {
			$scope.status = response;
		});

	})
	.controller( 'Groups', function( $scope, $http, $routeParams ) {
		
		$http.get( 'wp-json/social-api/v1/groups/' ).success( function( response ) {
			$scope.groups = response;
		});

	});