'use strict';
var app = angular.module('assessmentApp', ['ngAnimate', 'ui.bootstrap', 'monospaced.elastic', 'bootstrapLightbox']);
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

app.controller('assessmentController', ['$scope', '$http', '$animate', '$timeout', 'fileUpload', 'Lightbox', function($scope, $http, $animate, $timeout, $fileUpload, Lightbox) {
  $scope.asid = document.getElementById("asid").value;
  $scope.files = {};
  // var jsonStuff = {'questionType': 'Boolean','Boolean': {'true': 'Yes','false': 'No',0: 'No',1: 'Yes'}};
  // console.log(JSON.stringify(jsonStuff));
  $scope.next = function () {
    var thisPanel = angular.element( document.getElementsByClassName( 'panel' )[$scope.panel] );
    var nextPanel = angular.element( document.getElementsByClassName( 'panel' )[$scope.panel + 1] );
    thisPanel.removeClass('active');
    nextPanel.removeClass('hidden');
    $timeout(function() {
        $scope.panel++;
        thisPanel.addClass('hidden');
        nextPanel.addClass('active');
    }, 425);
  }
  $scope.prev = function () {
    var thisPanel = angular.element( document.getElementsByClassName( 'panel' )[$scope.panel] );
    var prevPanel = angular.element( document.getElementsByClassName( 'panel' )[$scope.panel - 1] );
    thisPanel.removeClass('active');
    prevPanel.removeClass('hidden');
    $timeout(function() {
        $scope.panel--;
        thisPanel.addClass('hidden');
        prevPanel.addClass('active');
    }, 425);
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

     // var uploadUrl = "/fileUpload";
     // fileUpload.uploadFileToUrl(file, uploadUrl, ids);
  };

  $scope.save = function () {
      console.log("saving");
  }

  $scope.openLightboxModal = function (images, index) {
    Lightbox.openModal(images, index);
  };

  $scope.questionTypes = ['demographic', 'project', 'artifact', 'scenario', 'attribute'];

  $http.post("models/assessment.php", {asid: $scope.asid}).then(function(response) {
    $scope.assessment = response.data;
    $scope.panel = 0;
    console.log(response.data);
    angular.forEach($scope.assessment.questions, function(value, key) {
        if(Object.keys(value).length > 0) {
            if ($scope.questionTypes.indexOf(key) !== -1) {
                angular.forEach(value, function(value, key) {
                    var data = value.questionData;
                    value.questionData = JSON.parse(data);
                    if(value.questionData.questionType == 'Check') {
                        var res = value.response;
                        value.response = JSON.parse(res);
                    }
                });
            } else {
                var data = value.questionData;
                value.questionData = JSON.parse(data);
            }
        } else {
            $scope.assessment.questions[key] = null;
        }
    });

  });

  // console.log($scope.ratings);

}]);

app.directive('questionTemplate', function() {
  return {
    templateUrl: 'partials/question.html'
  };
});

app.config(function (LightboxProvider) {
  LightboxProvider.templateUrl = 'partials/lightbox.html';
  LightboxProvider.fullScreenMode = true;
});