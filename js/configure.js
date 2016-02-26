'use strict';
var app = angular.module('configurator', ['ngAnimate', 'ui.bootstrap', 'bootstrapLightbox', 'ui.validate', 'ngCookies', 'ngFileUpload', 'teds.models']);


app.controller('configController', ['$scope', '$http', '$animate', '$timeout', 'Lightbox', '$anchorScroll', '$cookies', '$interval', 'projectService', '$q', 'Upload', function($scope, $http, $animate, $timeout, Lightbox, $anchorScroll, $cookies, $interval, projectService, $q, Upload) {

    projectService.getAllAssoc().then(function(response) {
        console.log(response);
    });

    $scope.addAlert = function() {
        $scope.alerts.push({msg: 'Another alert!'});
    };

    $scope.closeAlert = function(index) {
        $scope.alerts.splice(index, 1);
    };


}]);

// sets up the light box and changes the $location to allow us to rewrite the url
app.config(['LightboxProvider', '$locationProvider', function (LightboxProvider, $locationProvider) {
    LightboxProvider.templateUrl = 'partials/lightbox.html';
    LightboxProvider.fullScreenMode = true;
    // $locationProvider.html5Mode(true);

}]);