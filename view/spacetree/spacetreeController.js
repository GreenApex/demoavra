'use strict';

deskControllers.controller('spacetreeController', ['$scope', '$window',
    function ($scope, $window) {

        $("#loader").fadeOut();
        $scope.$on('$viewContentLoaded', function() {
            init()
        });



      



    }]);