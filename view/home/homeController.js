'use strict';

deskControllers.controller('homeController', ['$scope', '$window', 'login', '$cookies',
    function ($scope, $window, login, $cookies) {

        $("#loader").fadeOut();

       $scope.loginFunction = function(){
            $("#loader").fadeIn();
           login.save(
               {
                   USER_NAME : $scope.userName,
                   USER_COMPANY_NAME : $scope.companyName,
                   USER_PASS : $scope.password

               }, function (response){
                   alert(JSON.stringify(response));
                   $cookies.putObject('userData',response.data);
                   $.toaster("Login Successfully", 'Congratulation', 'success');
                   $window.location.href = "#/spacetree"
           });
       };
    }]);