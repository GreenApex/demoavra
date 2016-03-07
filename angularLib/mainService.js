'use strict';

deskServices.factory('login',['$resource','webAppConstant',
    function($resource,webAppConstant){
        return $resource(webAppConstant + 'checklogin.php', {USER_NAME:'@userName', USER_COMPANY_NAME:'@companyName', USER_PASS:'@password'}, {
            query: { method: "POST"}
        });
    }]);