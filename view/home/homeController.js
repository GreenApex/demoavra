'use strict';

deskControllers.controller('homeController', ['$scope', '$window',
    function ($scope, $window) {

        $("#loader").fadeOut();

        $scope.heading = "Landing Page...";

        //$("#show").click(function(){
        //    $(".show1").slideDown()
        //
        //});
        //
        //
        //
        //
        //$(document).click(function (e) {
        //    if (!$(e.target).hasClass("btn")
        //        && $(e.target).parents(".show1").length === 0)
        //    {
        //        $(".show1").hide();
        //    }
        //});
        //
        //$("#scrollTop").click(function() {
        //    $('html, body').animate({
        //        scrollTop: $(".second_section").offset().top
        //    }, 2000);
        //});
        //




    }]);