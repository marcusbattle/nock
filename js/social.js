angular.module('social', ['ngRoute'] )
	.config( function( $routeProvider, $locationProvider, $httpProvider ) {
		
		$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

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
    .controller( 'Login', function( $scope, $http, $routeParams ) {
    	
    	$scope.formData = {};

        $scope.submit = function() {

        	$http.post( 'wp-json/nock-app/v1/login', $scope.formData ).success(function(data){ 
				console.log( data );
			});

        }

    })
    .controller( 'Signup', function( $scope, $http, $routeParams ) {

		$scope.formData = {};

		$scope.submit = function() {

			console.log( JSON.stringify( $scope.formData ) );

			$http.post( 'wp-json/social-api/v1/request_access', $scope.formData ).success(function(data){ 
				console.log( data );
			});

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
