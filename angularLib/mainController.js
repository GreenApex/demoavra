'use strict';
//bootstrap ng-app="myApp"
angular.element(document).ready(function () {
    angular.bootstrap(document, ['myApp']);
});
//module for myApp decide route/controller/service/directive
var deskApp = angular.module('myApp', ['ngRoute', 'myControllers', 'myServices', 'dndLists']);

deskApp.constant('webAppConstant', 'http://54.172.65.114:8080/PrjMRI/filter/');

deskApp.config(['$routeProvider',
        function ($routeProvider) {
            $routeProvider.when('/home', {
                templateUrl: 'view/home/home.html',
                controller: 'homeController',
                access: {
                    requiresBackground: true
                },
                resolve: {
                    // I will cause a 1 second delay
                    delay: function ($q, $timeout) {
                        var delay = $q.defer();
                        $timeout(delay.resolve, 1000);
                        return delay.promise;
                    }
                }
            }).when('/spacetree', {
                templateUrl: 'view/spacetree/spacetree.html',
                controller: 'spacetreeController'

            }).when('/information', {
                templateUrl: 'view/information/information.html',
                controller: 'informationController'

            }).when('/bar', {
                templateUrl: 'view/bar/bar.html'

            }).otherwise({
                redirectTo: '/home'
            });
            //$locationProvider.html5Mode(true); //For Remove #
        }])
    .run(function ($rootScope, $location) {
        $rootScope.$on('$routeChangeStart', function (event, next) {


        });
    });



var deskControllers = angular.module('myControllers', []);

var deskServices = angular.module('myServices', ['ngResource']);
  
  
