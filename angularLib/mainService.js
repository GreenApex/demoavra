'use strict';

deskServices.factory('login',['$resource','webAppConstant',
    function($resource,webAppConstant){
        return $resource(webAppConstant + 'checklogin.php', {USER_NAME:'@USER_NAME', USER_COMPANY_NAME:'@USER_COMPANY_NAME', USER_PASS:'@USER_PASS'}, {
            query: { method: "POST"}
        });
    }]);

deskServices.factory('getinfromation',['$resource','webAppConstant',
    function($resource,webAppConstant){
        return $resource(webAppConstant + 'get_information.php', {USER_ID:'@USER_ID', STR_DATE:'@STR_DATE', END_DATE:'@END_DATE'}, {
            query: { method: "POST"}
        });
    }]);