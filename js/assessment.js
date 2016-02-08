'use strict';
var app = angular.module('assessmentApp', ['ngAnimate', 'ui.bootstrap', 'monospaced.elastic'])
   .controller('assessmentController', ['$scope', '$http', '$animate', '$timeout', function($scope, $http, $animate, $timeout) {
      $scope.asid = document.getElementById("asid").value;
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

      $scope.save = function () {
          console.log("saving");
      }

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
// app.animation('.panel', [function() {
//   return {
//     addClass: function(element, className, doneFn) {
//       // do some cool animation and call the doneFn
//     },
//     removeClass: function(element, className, doneFn) {
//       // do some cool animation and call the doneFn
//     },
//     setClass: function(element, addedClass, removedClass, doneFn) {
//       // do some cool animation and call the doneFn
//     }
//   }
// }]);