'use strict';
var app = angular.module('assessmentApp', ['ngAnimate', 'ui.bootstrap', 'monospaced.elastic', 'bootstrapLightbox', 'ui.validate', 'ngCookies']);
app.directive('fileModel', ['$parse', function ($parse) {
    return {
       restrict: 'A',
       link: function(scope, element, attrs) {
          var model = $parse(attrs.fileModel);
          var modelSetter = model.assign;

          element.bind('change', function(){
             scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
             });
          });
       }
    };
 }]);

app.service('fileUpload', ['$http', function ($http) {
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
        var target = "models/screenshot.php?f=delete";

        $http.post(target, data, {
        }).success(function(response){
            deferred.resolve(response);
        }).error(function(response){
            deferred.reject(response);
        });
        return deferred.promise;
    }
}]);

app.controller('assessmentController', ['$scope', '$http', '$animate', '$timeout', 'fileUpload', 'Lightbox', '$location', '$anchorScroll', '$cookies', '$interval', 'assessmentService', 'userService', '$q', function($scope, $http, $animate, $timeout, $fileUpload, Lightbox, $location, $anchorScroll, $cookies, $interval, assessmentService, userService, $q) {
    $scope.asid = document.getElementById("asid").value;
    $scope.files = {};
    $scope.panel = 0;
    $scope.ready = false;

    var panels = getPanels();
    // $scope.hasChanges = false;
    var authCookie = $cookies.get('teds_userIDAuthed');



    // starts the save timer and gets the assessment of the url
    var savePromise = $interval(save, 30000);

    getAssessment($scope.asid).then(function(response){
        $scope.assessment = response;
        if($scope.assessment.user.userID === authCookie && authCookie !== undefined) {
            $scope.userValidated = true;
        } else {
            console.log('not valid');
             $scope.userValidated = false;
        }

        //wait for dom to evaluate hack
        $timeout(function() {
                $scope.setReady();
        }, 1500);
    });

    $scope.setReady = function () {
        $scope.ready = true;
        return true;
    }

    function save() {
        console.log('saving');
    }
    $scope.save = {
        assessment: function() {

        },
        rating: function(attribute) {
            var data = {
                attribute: attribute,
                assessmentID: $scope.assessment.assessmentID
            };
            ratingService.put(data).then(function(response) {
                attribute.ratingID = response.ratingID;
            });
        },
        response: function(question) {
            var data = {
                question: question,
                assessmentID: $scope.assessment.assessmentID
            };
            responseService.put(data).then(function(response) {
                question.responseID = response.responseID;
            });
        },
        comment: function(attribute) {
            if(attribute.ratingID == undefined) {
                var data = {
                    attribute: attribute,
                    assessmentID: $scope.assessment.assessmentID
                };
                ratingService.put(data).then(function(response) {
                    attribute.ratingID = response.ratingID;
                });
            }
            var data = {
                attribute: attribute,
            };
            commentService.put(data).then(function(response) {
                attribute.commentID = response.commentID;
            });

        },
        screenshot: function(attribute) {
            if(attribute.ratingID == undefined) {
                var data = {
                    attribute: attribute,
                    assessmentID: $scope.assessment.assessmentID
                };
                ratingService.put(data).then(function(response) {
                    attribute.ratingID = response.ratingID;
                });
            }
            var data = {
                attribute: attribute,
            };
            screenshotService.put(data).then(function(response) {
            });
        }
    };

    // go to the next panel
    $scope.next = function () {
        panels = getPanels();
        var thisPanel = angular.element(panels[$scope.panel]);
        var nextPanel = angular.element(panels[$scope.panel + 1]);
        thisPanel.removeClass('active');
        nextPanel.removeClass('hidden');
        $timeout(function() {
            $scope.panel++;
            thisPanel.addClass('hidden');
            nextPanel.addClass('active');
        }, 425);
    }

    // go to the previous panel
    $scope.prev = function () {
        panels = getPanels();
        var thisPanel = angular.element(panels[$scope.panel]);
        var prevPanel = angular.element(panels[$scope.panel - 1]);
        thisPanel.removeClass('active');
        prevPanel.removeClass('hidden');
        $timeout(function() {
            $scope.panel--;
            thisPanel.addClass('hidden');
            prevPanel.addClass('active');
        }, 425);
    }

    $scope.last = function  () {
        var thisPanel = angular.element(panels[$scope.panel]);
        var lastPanel = angular.element(panels[panels.length - 1]);
        thisPanel.removeClass('active');
        lastPanel.removeClass('hidden');
        $timeout(function() {
            $scope.panel = panels.length - 1;
            thisPanel.addClass('hidden');
            lastPanel.addClass('active');
        }, 425);
    }

    function getPanels () {
        return angular.element( document.getElementsByClassName( 'panel' ));
    }


    $scope.uploadFile = function(fileKey){
    console.log(fileKey);
     var file = $scope.files[fileKey];
     var ids = {
        assessmentID: $assessment.assessmentID,
        attributeID: fileKey
     };

     console.log('file is ' );
     console.dir(file);

     var uploadUrl = "uploadScreenshot.php";
     fileUpload.uploadFileToUrl(file, uploadUrl, ids);
    };

    $scope.openLightboxModal = function (images, index) {
        Lightbox.openModal(images, index);
    };

    // validates the user from the signin form and then if there is a user mismatch it either reattaches the form to the new user, creates a new form or gets the other users copy of the form.
    $scope.validateUser = function(form) {
        if(form.$valid && form.$dirty) {
            console.log('validating user');
            console.log("form");
            console.log(form);
            var email = form.email.$modelValue;
            var password = form.password.$modelValue;

            //gets the user

            $http.post("models/user.php?f=get", {email: email, password: password}).then(function(response) {
                var user = response.data.user;
                console.log(user);
                if (user == false) {
                    // alert the dom that there was no matching user
                    form.$error.notFound = true;
                    console.log("That user/password combination did not match any in our database.");

                } else {
                    // there was a match
                    form.$error.notFound = false;

                    // set their authed cookie
                    var tenHours = new Date(new Date().setHours(new Date().getHours() + 10));
                    $cookies.put('teds_userIDAuthed', user.userID, {'expires': tenHours});

                    //check if the assessments user is the same as the one from signin
                    if (user.userID !== $scope.assessment.user.userID) {
                        //checks if new user has a copy of the form
                        $http.post("models/assessment.php?f=getByUserConf", {userID: user.userID, configurationID: $scope.assessment.configurationID}).then(function(response) {
                            var assessment = response.data.assessment;
                            console.log(response.data);

                            if(assessment == false || assessment == undefined) {
                                //if no copy
                                console.log("no assessment");
                                if($scope.assessment.user.email == null || $scope.assessment.user.email == undefined) {
                                    // this form belonged to a temp user it is takeable

                                    // assume that this user was the temp and has lost their persistant cookie
                                    var oneYear = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
                                    $cookies.put('teds_userID', user.userID, {'expires': oneYear});

                                    console.log('this is a temp user. transfering assessment and deleting.');
                                    $http.post("models/assessment.php?f=updateUser", {userID: user.userID, assessmentID: $scope.assessment.assessmentID}).then(function(response) {

                                        console.log(response.data);

                                        if(response.data.updated) {
                                            $http.post("models/user.php?f=delete", {userID: $scope.assessment.user.userID}).then(function(response) {

                                                console.log(response.data);

                                                if(response.data.deleted) {
                                                    console.log("temp user was deleted.");
                                                    $scope.assessment.user = user;
                                                    $scope.next();
                                                    $timeout(function() {
                                                        $scope.userValidated = true;
                                                        $scope.panel--;
                                                    }, 500);
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    // you cannot take this form
                                    console.log('this is a fully registered user. creating a new assessment and transfering.');
                                    window.location = "start.php?c=" + $scope.assessment.configurationIDHashed;
                                }


                            } else {
                                //user has a copy
                                console.log("yes! this users assessment exists. transfering to new assessment");
                                getAssessment(assessment.assessmentIDHashed);
                                $scope.next();
                                $timeout(function() {
                                    $scope.userValidated = true;
                                    $scope.panel--;
                                }, 500);
                                $location.search('asid', assessment.assessmentIDHashed);
                                $location.replace();
                            }
                        });
                    } else {
                        console.log("all good, everything matches!");
                        $scope.next();
                        $timeout(function() {
                            $scope.userValidated = true;
                            $scope.panel--;
                        }, 500);
                    }
                }

            });

        }

    }

    $scope.addUser = function(form) {
        if(form.$valid && form.$dirty) {
            console.log("adding user");
            var email = form.email.$modelValue;
            var password = form.password.$modelValue;
            console.log(form);

            userService.post({id: $scope.assessment.user.userID, email: email, password: password}).then(function(response) {
                if (response.user) {
                    var tenHours = new Date(new Date().setHours(new Date().getHours() + 10));
                    $cookies.put('teds_userIDAuthed', $scope.assessment.user.userID, {'expires': tenHours});

                    $scope.next();
                    $timeout(function() {
                        $scope.userValidated = true;
                        $scope.panel--;
                    }, 500);
                }
            });
        }
    }






    function getAssessment (asid) {
        $scope.questionTypes = ['demographic', 'project', 'artifact', 'scenario', 'attribute'];

        var deferred = $q.defer();
        var assessment;
        return assessmentService.get({asid: asid}).then(function(response) {
            assessment = response;
            $scope.showSignin = assessment.user.email ? true : false;
            $scope.requiredItems = 0;
            $scope.completedItems = 0;
            console.log(assessment);

            // assemble question data and calculate completed and required questions
            angular.forEach(assessment.questions, function(questionCategory, questionCategoryKey) {
                if(Object.keys(questionCategory).length > 0) {
                    if ($scope.questionTypes.indexOf(questionCategoryKey) !== -1) {
                        angular.forEach(questionCategory, function(question, questionKey) {
                            var data = question.questionData;
                            question.questionData = JSON.parse(data);
                            if(question.questionData.questionType == 'Check') {
                                var res = question.response;
                                if(res) {
                                    question.response = JSON.parse(res);
                                }
                            }
                            if(question.questionRequired == 1) {
                                $scope.requiredItems++;
                                if(question.response !=='' && question.response) {
                                    $scope.completedItems++;
                                }
                            }

                        });
                    } else {
                        var data = questionCategory.questionData;
                        questionCategory.questionData = JSON.parse(data);
                    }
                } else {
                    assessment.questions[questionCategoryKey] = null;
                }
            });

            // calculate completed attributes
            angular.forEach(assessment.criteria, function(criterion, criterionKey) {
                angular.forEach(criterion.attributes, function(attribute, attributeKey) {
                    if(attribute.ratingValue !== '' && attribute.ratingValue) {
                        $scope.completedItems++;
                    }
                    $scope.requiredItems++;
                });
            });
            deferred.resolve(assessment);
            return deferred.promise;
        }); // end get assessment promise
    } // end get assessment function



    $scope.trackProgress = function (newValue, oldValue, required) {
        if(required) {
            if(oldValue == '') {
                $scope.completedItems++;
            }
            if(newValue == '') {
                $scope.completedItems--;
            }
        }
    }


}]);

app.directive('questionTemplate', function() {
  return {
    templateUrl: 'partials/question.html'
  };
});

app.directive('panelNavigation', function() {
  return {
    template: '<div class="col-xs-2"><a class="btn btn-block btn-primary prev" ng-click="prev()" scroll scroll-target="#header">Back</a></div><div class="col-xs-8"><uib-progressbar max="requiredItems" value="completedItems"><span style="color:white; white-space:nowrap;">{{completedItems}} / {{requiredItems}}</span></uib-progressbar></div><div class="col-xs-2"><a class="btn btn-block btn-primary next" ng-click="next()" scroll scroll-target="#header">Continue</a></div>'
  };
});

app.directive('scroll', function() {
    return {
        restrict: 'A',
        scope: {
            scrollTarget: "@"
        },
        link: function(scope, $elm,attr) {
            $elm.on('click', function() {
                $('html,body').animate({scrollTop: $(scope.scrollTarget).offset().top }, "slow");
            });
        }
    }
});

app.config(['LightboxProvider', '$locationProvider', function (LightboxProvider, $locationProvider) {
    LightboxProvider.templateUrl = 'partials/lightbox.html';
    LightboxProvider.fullScreenMode = true;
    $locationProvider.html5Mode(true);

}]);

app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});