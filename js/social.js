angular.module('social', ['ngRoute'] )
	.config( function( $routeProvider, $locationProvider) {
		
		$locationProvider.html5Mode( true );

		$routeProvider
			.when('/', {
				templateUrl: social.views + '/status-home.php',
				controller: 'Home'
			})
			.when('/signup', {
				templateUrl: social.views + '/signup.php',
				controller: 'Signup'
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
    .controller( 'Signup', function( $scope, $http, $routeParams ) {

    	// create a blank object to hold our form information
		// $scope will allow this to pass between controller and view
		$scope.formData = {};

		$scope.submit = function() {
			alert('come on bruh!');
		}

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
