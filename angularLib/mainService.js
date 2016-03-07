'use strict';

/*deskServices.factory(['authentication',
    function (authentication) {
        var authorize = function (loginRequired, requiredPermissions, permissionCheckType) {
            var result = jcs.modules.auth.enums.authorised.authorised,
                user = authentication.getCurrentLoginUser(),
                loweredPermissions = [],
                hasPermission = true,
                permission, i;

            permissionCheckType = permissionCheckType || jcs.modules.auth.enums.permissionCheckType.atLeastOne;
            if (loginRequired === true && user === undefined) {
                result = jcs.modules.auth.enums.authorised.loginRequired;
            } else if ((loginRequired === true && user !== undefined) &&
                (requiredPermissions === undefined || requiredPermissions.length === 0)) {
                // Login is required but no specific permissions are specified.
                result = jcs.modules.auth.enums.authorised.authorised;
            } else if (requiredPermissions) {
                loweredPermissions = [];
                angular.forEach(user.permissions, function (permission) {
                    loweredPermissions.push(permission.toLowerCase());
                });

                for (i = 0; i < requiredPermissions.length; i += 1) {
                    permission = requiredPermissions[i].toLowerCase();

                    if (permissionCheckType === jcs.modules.auth.enums.permissionCheckType.combinationRequired) {
                        hasPermission = hasPermission && loweredPermissions.indexOf(permission) > -1;
                        // if all the permissions are required and hasPermission is false there is no point carrying on
                        if (hasPermission === false) {
                            break;
                        }
                    } else if (permissionCheckType === jcs.modules.auth.enums.permissionCheckType.atLeastOne) {
                        hasPermission = loweredPermissions.indexOf(permission) > -1;
                        // if we only need one of the permissions and we have it there is no point carrying on
                        if (hasPermission) {
                            break;
                        }
                    }
                }

                result = hasPermission ?
                    jcs.modules.auth.enums.authorised.authorised :
                    jcs.modules.auth.enums.authorised.notAuthorised;
            }

            return result;
        };

        return {
            authorize: authorize
        };
    }]);*/

deskServices.factory('getCategories', ['$resource',
    function($resource){
        //alert("1");
        return $resource("json/" + ':verb', {verb:'category.json'}, {
            query: { method: "GET"}
        });

    }]);

deskServices.factory('getKeyList', ['$resource','webAppConstant',
    function($resource,webAppConstant){
        return $resource(webAppConstant + ':verb', {verb:'getkeylist'}, {
            query: { method: "GET"}
        });
    }]);

deskServices.factory('getPinCode', ['$resource',
    function($resource){
        return $resource("http://maps.googleapis.com/maps/api/geocode/" + ':verb', {verb:'json',address:'@address'}, {
            query: { method: "GET"}
        });
    }]);

/*mriApp.service('uploadFiles', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var fd = new FormData();
        fd.append('file', file);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
            .success(function(){
            })
            .error(function(){
            });
    }
}]);*/

/*nearbyServices.factory('forgetPassword', ['$resource','webAppConstant',
 function($resource,webAppConstant){
 return $resource(webAppConstant + ':verb', {verb:'forgetpassword',email:'@email'}, {
 query: { method: "POST" }

 });

 }]);*/

