'use strict';
var app = angular.module('assessmentApp', ['ngAnimate', 'ui.bootstrap', 'monospaced.elastic', 'bootstrapLightbox', 'ui.validate', 'ngCookies', 'ngFileUpload', 'teds.models']);
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

app.controller('assessmentController', ['$scope', '$http', '$animate', '$timeout', 'Lightbox', '$location', '$anchorScroll', '$cookies', '$interval', 'assessmentService', 'userService', '$q', 'ratingService', 'responseService', 'commentService', 'screenshotService', 'Upload', function($scope, $http, $animate, $timeout, Lightbox, $location, $anchorScroll, $cookies, $interval, assessmentService, userService, $q, ratingService, responseService, commentService, screenshotService, Upload) {
    $scope.asid = document.getElementById("asid").value;
    $scope.files = {};
    $scope.panel = 0;
    $scope.ready = false;
    $scope.finished = false;
    $scope.alerts = [];

    var panels = getPanels();
    var authCookie = $cookies.get('teds_userIDAuthed');


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


    $scope.save = {
        assessment: function() {

        },
        finish: function() {
            var data = {
                assessmentID: $scope.assessment.assessmentID
            }
            assessmentService.finish(data).then(function(response) {
                $scope.finished = true;
            });
        },
        rating: function(attribute) {
            var deferred = $q.defer();
            var data = {
                attribute: attribute,
                assessmentID: $scope.assessment.assessmentID
            };
            return ratingService.put(data).then(function(response) {
                attribute.ratingID = response.data.ratingID;
                deferred.resolve(response.data.ratingID);
                return deferred.promise;
            });
        },
        response: function(question) {
            var deferred = $q.defer();
            var data = {
                question: question,
                assessmentID: $scope.assessment.assessmentID
            };
            if(question.assessmentID !== $scope.assessment.assessmentID) {
                data.assessmentID = question.assessmentID;
            };
            return responseService.put(data).then(function(response) {
                question.responseID = response.data.responseID;
                deferred.resolve(response.data.responseID);
                return deferred.promise;
            });
        },
        comment: function(attribute) {
            var deferred = $q.defer();
            var data = {
                attribute: attribute,
            };
            if(attribute.ratingID == undefined) {
                return $scope.save.rating(attribute).then(function(ratingID) {
                    return commentService.put(data).then(function(response) {
                        attribute.commentID = response.data.commentID;
                        deferred.resolve(response.data.commentID);
                        return deferred.promise;
                    });
                });
            } else {
                return commentService.put(data).then(function(response) {
                    attribute.commentID = response.data.commentID;
                    deferred.resolve(response.data.commentID);
                    return deferred.promise;
                });
            }



        },

        screenshot: function(attribute) {
            var deferred = $q.defer();

            if($scope.files[attribute.attributeID] !== null) {

                if($scope.files[attribute.attributeID].size < 2097152) {
                    $scope.uploadFile(attribute).then(function(path) {
                        var filePath = path;
                        console.log(filePath);

                        if(attribute.ratingID == undefined) {
                            // create a blank rating to attach screenshot
                            return $scope.save.rating(attribute).then(function(ratingID) {
                                var data = {
                                    path: filePath,
                                    ratingID: attribute.ratingID
                                };
                                console.log(data);

                                return screenshotService.put(data).then(function(response) {
                                    attribute.screenshots.push(filePath);
                                    deferred.resolve(response.data);
                                    return deferred.promise;
                                });
                            });
                        } else {
                            var data = {
                                path: filePath,
                                ratingID: attribute.ratingID
                            };
                            console.log(data);

                            return screenshotService.put(data).then(function(response) {
                                attribute.screenshots.push(filePath);
                                deferred.resolve(response.data);
                                return deferred.promise;
                            });
                        }
                    });
                } else {
                    $scope.alerts.push({type: 'danger', msg: 'Files must be less than 2mb in size!'});
                    deferred.reject("too large");
                    return deferred.promise;
                }
            }
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

    // go to the last panel 'finshed'
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

    // return all the current panels
    function getPanels () {
        return angular.element( document.getElementsByClassName( 'panel' ));
    }

    //uploads a file
    $scope.uploadFile = function(attribute){
        var file = $scope.files[attribute.attributeID];
        return Upload.upload({
            url: 'upload.php?t=screenshot',
            data: {
                file: file,
                assessmentID: $scope.assessment.assessmentID,
                attributeID: attribute.attributeID
            }
        }).then(function (resp) {
            console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
            delete attribute.progressPercentage;
            return resp.data.path;
        }, function (resp) {
            console.log('Error status: ' + resp.status);
            delete attribute.progressPercentage;
        }, function (evt) {
            attribute.progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
            console.log('progress: ' + attribute.progressPercentage + '% ' + evt.config.data.file.name);
        });
    }

    $scope.openLightboxModal = function (images, index) {
        Lightbox.openModal(images, index);
    };

    // validates the user from the signin form and then if there is a user mismatch it either reattaches the form to the new user, creates a new form or gets the other users copy of the form.
    $scope.validateUser = function(form) {
        if(form.$valid && form.$dirty) {
            console.log('validating user');
            var email = form.email.$modelValue;
            var password = form.password.$modelValue;

            //gets the user
            userService.validate(email, password).then(function(response) {
                var user = response.data.user;
                if (user == false) {
                    // alert the dom that there was no matching user
                    form.$error.notFound = true;
                    // console.log("That user/password combination did not match any in our database.");

                } else {
                    // there was a match
                    form.$error.notFound = false;

                    // set their authed cookie
                    var tenHours = new Date(new Date().setHours(new Date().getHours() + 10));
                    $cookies.put('teds_userIDAuthed', user.userID, {'expires': tenHours, 'path': '/'});
                    // assume that this user has lost their persistant cookie
                    var oneYear = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
                    $cookies.put('teds_userID', user.userID, {'expires': oneYear, 'path': '/'});

                    //check if the assessments user is the same as the one from signin
                    if (user.userID !== $scope.assessment.user.userID) {
                        //checks if new user has a copy of the form
                        assessmentService.getByUserConf({userID: user.userID, configurationID: $scope.assessment.configurationID}).then(function(response) {
                            var assessment = response.data.assessment;
                            if(assessment == false || assessment == undefined) {
                                //if no copy
                                if($scope.assessment.user.email == null || $scope.assessment.user.email == undefined) {
                                    // this form belonged to a temp user it is takeable
                                    assessmentService.updateUser({userID: user.userID, assessmentID: $scope.assessment.assessmentID}).then(function(response) {
                                        if(response.data.updated) {
                                            userService.delete($scope.assessment.user.userID).then(function(response) {
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
                                    window.location = "start.php?c=" + $scope.assessment.configurationIDHashed;
                                }
                            } else {
                                //user has a copy
                                if($scope.assessment.user.email == null || $scope.assessment.user.email == undefined) {
                                    // this form belonged to a temp user it is deleteable
                                    //delete assessment
                                    assessmentService.delete($scope.assessment.assessmentID).then(function(response) {
                                        if(response.data.deleted) {
                                            userService.delete($scope.assessment.user.userID).then(function(response) {
                                                if(response.data.deleted) {
                                                    getAssessment(assessment.assessmentIDHashed).then(function(response){
                                                        $scope.assessment = response;
                                                        $scope.next();
                                                        $timeout(function() {
                                                            $scope.userValidated = true;
                                                            $scope.panel--;
                                                        }, 500);
                                                        $location.search('asid', assessment.assessmentIDHashed);
                                                        $location.replace();
                                                    });
                                                }
                                            });
                                        }
                                    });
                                } else {
                                    // this form is owned just retrieve your own
                                    getAssessment(assessment.assessmentIDHashed).then(function(response){
                                        $scope.assessment = response;
                                        $scope.next();
                                        $timeout(function() {
                                            $scope.userValidated = true;
                                            $scope.panel--;
                                        }, 500);
                                        $location.search('asid', assessment.assessmentIDHashed);
                                        $location.replace();
                                    });
                                }


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

    // converts a temp user into a full user
    $scope.addUser = function(form) {
        if(form.$valid && form.$dirty) {
            console.log("adding user");
            var email = form.email.$modelValue;
            var password = form.password.$modelValue;
            console.log(form);

            userService.post({id: $scope.assessment.user.userID, email: email, password: password}).then(function(response) {
                if (response.data.user) {
                    var tenHours = new Date(new Date().setHours(new Date().getHours() + 10));
                    $cookies.put('teds_userIDAuthed', $scope.assessment.user.userID, {'expires': tenHours, 'path': '/'});

                    $scope.next();
                    $timeout(function() {
                        $scope.userValidated = true;
                        $scope.panel--;
                    }, 500);
                }
            });
        }
    }

    $scope.taken = function(form) {
        if(form.$dirty && !form.email.$error.email) {
            var email = form.email.$modelValue;
            console.log(email);
            userService.findEmail(email).then(function(response) {
                if(response.data.exists) {
                    console.log('was taken');
                    form.email.$error.taken = true;
                    form.$valid = false;
                } else {
                    console.log('was taken');
                    form.email.$error.taken = false;
                    // form.$valid = false;
                }

            });
        }
    }





    //gets the data for an assessment and formats it correctly
    function getAssessment (asid) {
        $scope.questionTypes = ['demographic', 'project', 'artifact', 'scenario', 'attribute'];

        var deferred = $q.defer();
        var assessment;
        return assessmentService.get({asid: asid}).then(function(response) {
            assessment = response.data;
            $scope.showSignin = assessment.user.email ? true : false;
            $scope.requiredItems = 0;
            $scope.completedItems = 0;
            console.log(assessment);

            // assemble question data and calculate completed and required questions
            angular.forEach(assessment.questions, function(questionCategory, questionCategoryKey) {
                if(Object.keys(questionCategory).length > 0) {
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
                    assessment.questions[questionCategoryKey] = null;
                }
            });

            // calculate completed attributes
            angular.forEach(assessment.criteria, function(criterion, criterionKey) {
                angular.forEach(criterion['attributes'], function(attribute, attributeKey) {

                    if(attribute.ratingValue !== '' && attribute.ratingValue && attribute.ratingValue !== "0") {
                        $scope.completedItems++;
                    }
                    if(attribute.ratingValue == "0") {
                        attribute.ratingValue = '';
                    }
                    $scope.requiredItems++;
                    attribute.attributeDesc = JSON.parse(attribute.attributeDesc);
                    attribute.attributeLaymanDesc = JSON.parse(attribute.attributeLaymanDesc);
                    if (attribute.attributeTypeName == 'Cluster') {
                        angular.forEach(attribute.categories, function(category, categoryKey) {
                            category.attributeDesc = JSON.parse(category.attributeDesc);
                            category.attributeLaymanDesc = JSON.parse(category.attributeLaymanDesc);
                        });

                    }

                });
            });
            deferred.resolve(assessment);
            return deferred.promise;
        }); // end get assessment promise
    } // end get assessment function


    // tracks progress for the progress bar
    $scope.trackProgress = function (newValue, oldValue, required) {
        var oldValue = decodeURI(oldValue);
        if(required) {
            if(oldValue == '' || oldValue == undefined || oldValue== null) {
                $scope.completedItems++;
            }
            if(newValue == '' || newValue == undefined || newValue== null) {
                $scope.completedItems--;
            }
        }
    }

    $scope.addAlert = function() {
        $scope.alerts.push({msg: 'Another alert!'});
    };

    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };


}]);

app.directive('questionTemplate', function() {
  return {
    templateUrl: 'partials/question.html'
  };
});

app.directive('panelNavigation', function() {
  return {
    templateUrl: 'partials/navigation.html'
  };
});

// scrolls the user to the target
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

// sets up the light box and changes the $location to allow us to rewrite the url
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