'use strict';

deskControllers.controller('informationController', ['$scope', '$window', '$cookies', '$location', 'getinfromation',
    function ($scope, $window, $cookies, $location, getinfromation) {

        $(".loader").fadeIn();
        //var userData = $cookies.getObject('userData');

        //$scope.userName = userData.USER_NAME;
        //console.log('Information userData : ' +JSON.stringify($scope.userName));

        var searchObject = $location.search();
        $scope.USER_NAME = searchObject.USER_NAME;
        $scope.USER_ID = searchObject.USER_ID;
        $scope.STR_DATE = searchObject.STR_DATE;
        $scope.END_DATE = searchObject.END_DATE;
        $scope.userName = $scope.USER_NAME;
        console.log('Information userID : ' +JSON.stringify($scope.USER_ID));

        getinfromation.get(
            {
                USER_ID: $scope.USER_ID,
                STR_DATE: $scope.STR_DATE,
                END_DATE: $scope.END_DATE
            }, function (response) {
                //$(".loader").fadeIn();
                console.log('Get Information : ' +JSON.stringify(response));
                if(response.status == 1){
                    $(".loader").fadeOut();
                    $scope.getInformation = response.data;
                    $.toaster(response.msg, 'Congratulation', 'success');
                    console.log('Get Information : ' +JSON.stringify($scope.getInformation));
                }
                else{
                    $(".loader").fadeOut();
                    $scope.msg = "Not Data Avialable...";
                    $.toaster(response.msg, 'Alert', 'warning');
                }
        },function(){
                $.toaster("Connection Problem ", 'Alert', 'danger');
        });
    }]);

