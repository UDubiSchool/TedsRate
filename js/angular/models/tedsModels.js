'use strict';
var app = angular.module('teds.models', []);

app.service('uploadService', ['$http', '$q', function ($http, $q) {
    this.uploadFileToUrl = function(file, uploadUrl){
        var deferred = $q.defer();
        var fd = new FormData();
        fd.append('file', file);

        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }
}]);

app.service('projectService', ['$http', '$q', function ($http, $q) {
    this.get = function(id) {
        var deferred = $q.defer();
        var target = "CI/index.php/api/project/29/json/";
        var data = {};
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    this.getAll = function() {
        var deferred = $q.defer();
        var target = "CI/index.php/api/project/json/";
        var data = {};
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    this.delete = function(id) {
        var deferred = $q.defer();
        var target = "models/project.php?f=delete";
        var data = {
            id: id
        };
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

}]);

app.service('userService', ['$http', '$q', function ($http, $q) {

    // validates a email-password pair
    this.validate = function(email, password){
        var deferred = $q.defer();
        var target = "models/user.php?f=get";
        var data = {
            email: email,
            password: password
        };
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    this.findEmail = function(email) {
        var deferred = $q.defer();
        var target = "models/user.php?f=getEmail";
        var data = {
            email: email
        };
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    //updates a user
    this.post = function(data){
        var deferred = $q.defer();
        var target = "models/user.php?f=post";
        // var data = data;
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    // deletes the user corrosponding to the ID
    this.delete = function(userID){
        var deferred = $q.defer();
        var target = "models/user.php?f=delete";
        // var data = data;
        $http.post(target, {userID: userID}, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }
}]);

app.service('assessmentService', ['$http', '$q', function ($http, $q) {
    // gets a single assessment based on the hashed ID
    this.get = function(data){
        var deferred = $q.defer();
        var target = "models/assessment.php?f=get";
        // var data = data;
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    // gets an assessment based on the unique key (userID, ConfigurationID)
    this.getByUserConf = function(data){
        var deferred = $q.defer();
        var target = "models/assessment.php?f=getByUserConf";
        // var data = data;
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    // updates an assessments user
    this.updateUser = function(data){
        var deferred = $q.defer();
        var target = "models/assessment.php?f=updateUser";
        // var data = data;
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    // saves the assessment
    this.save = function(data){
        var deferred = $q.defer();
        var target = "models/assessment.php?f=save";
        // var data = data;
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    // saves the assessment
    this.finish = function(data){
        var deferred = $q.defer();
        var target = "models/assessment.php?f=finish";
        // var data = data;
        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

}]);

app.service('ratingService', ['$http', '$q', function ($http, $q) {

    //adds a rating
    this.put = function(data){
        var deferred = $q.defer();
        var target = "models/rating.php?f=put";

        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }
}]);

app.service('responseService', ['$http', '$q', function ($http, $q) {

    //adds a response
    this.put = function(data){
        var deferred = $q.defer();
        var target = "models/response.php?f=put";

        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }
}]);

app.service('commentService', ['$http', '$q', function ($http, $q) {

    //adds a comment
    this.put = function(data){
        var deferred = $q.defer();
        var target = "models/comment.php?f=put";

        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    this.delete = function(data){
        var deferred = $q.defer();
        var target = "models/comment.php?f=delete";

        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }
}]);

app.service('screenshotService', ['$http', '$q', function ($http, $q) {

    //adds a screenshot
    this.put = function(data){
        var deferred = $q.defer();
        var target = "models/screenshot.php?f=put";

        return $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }

    this.delete = function(data){
        var deferred = $q.defer();
        var target = "models/screenshot.php?f=delete";

        return $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }
}]);