'use strict';

deskControllers.controller('spacetreeController', ['$scope', '$window', '$cookies',
    function ($scope, $window, $cookies) {

        $(".loader").fadeIn();

        var userData = $cookies.getObject('userData');

        console.log('userData : ' +JSON.stringify(userData));

        $scope.$on('$viewContentLoaded', function() {
            $(".loader").fadeOut();
            console.log('Spacetree :');
            init()
        });



      



    }]);