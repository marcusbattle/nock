var nock_app = angular.module('nock', ['ngRoute','ngCookies'] );

nock_app.config( function( $routeProvider, $locationProvider, $httpProvider ) {
		
	$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

	// $locationProvider.html5Mode( true ); // Remove the '#' from URL.

	$routeProvider			
		.when('/', {
			templateUrl: nock.views + '/statuses.php',
			controller: 'Home'
		})
		.when('/login', {
			templateUrl: nock.views + '/login.php',
			controller: 'Login'
		})
		.when('/status/:ID', {
			templateUrl: nock.views + '/status.php',
			controller: 'Single'
		})
		.when('/signup', {
			templateUrl: nock.views + '/signup.php',
			controller: 'Signup'
		})
		.when('/groups', {
			templateUrl: nock.views + '/groups-home.php',
			controller: 'Groups'
		})
		.when('/groups/:ID', {
			templateUrl: nock.views + '/single-group.php',
			controller: 'SingleGroup'
		})
		.when('/networks', {
			templateUrl: nock.views + '/networks.php',
			controller: 'Networks'
		});

});

nock_app.run(['$rootScope', '$location', 'authProvider', function ( $rootScope, $location, authProvider ) {
       
    $rootScope.$on( '$routeChangeStart', function ( event, next, current ) {
    
        if ( ! authProvider.isLoggedIn() ) {
			
			$location.path('login');

        }

  	});

}]);

nock_app.factory( 'authProvider', function( $cookies ) {
	
	var loginStatus = $cookies.get('nock_access_token');
    
    return {
        
        isLoggedIn : function() {
			return ( loginStatus ) ? loginStatus : false;
        }

	};

});


nock_app.controller( 'Home', function( $scope, $http, $routeParams, $cookies ) {

		$http.get( 'wp-json/nock-app/v1/statuses' ).success( function( response ) {
			$scope.statuses = response;
		});
    	
    })
    .controller( 'Networks', function( $scope, $http, $routeParams, $cookies ) {

    	$http.get( 'wp-json/nock-app/v1/networks' ).success( function( response ) {
			$scope.networks = response;
		});

    	$scope.setNetwork = function( item ) {
    		console.log( item.currentTarget.dataset['networkId'] );
    	}

    })
    .controller( 'Login', function( $scope, $cookies, $http, $routeParams, $location, authProvider ) {
    	
    	$scope.formData = {};
    	
        $scope.submit = function() {

        	$http.post( 'wp-json/nock-app/v1/login', $scope.formData ).success( function( data ) { 
				
				if ( data.success ) {
					
					$cookies.put( 'logged_in', true );
					$location.path('/');

				}

			});

        }

    })
    .controller( 'Signup', function( $scope, $http, $routeParams ) {

		$scope.formData = {};

		$scope.submit = function() {

			$http.post( 'wp-json/social-api/v1/request_access', $scope.formData ).success(function(data){ 
				
				if ( data.success ) {

				} else {

				}

			});

		}

    })
    .controller( 'Single', function( $scope, $http, $routeParams ) {
		
		$http.get( 'wp-json/social-api/v1/statuses/' + $routeParams.ID ).success( function( response ) {
			$scope.status = response;
			$scope.comments = response.comments;
		});

	})
	.controller( 'Groups', function( $scope, $http, $routeParams ) {
		
		$http.get( 'wp-json/social-api/v1/groups/' ).success( function( response ) {
			$scope.groups = response;
		});

	});