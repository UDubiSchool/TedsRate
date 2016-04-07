'use strict';
var app = angular.module('teds.directives.pivotTable', ['AngularPrint'])
.constant('pathConst',{
    pathToDir: 'js/angular/common/directives/pivotTable/'
})
.directive('tedsPivotTable', function(){
    return {
        restrict: 'E',
        // transclude: true,
        scope: {
            tedsData: '=',
            tedsOptions: '='
        },
        controller: ['$scope', '$timeout', function($scope, $timeout) {
            console.log($scope);
            $scope.options = $scope.tedsOptions;
            $scope.data = $scope.tedsData;

            if ($scope.options.colorize) {
                $scope.minColor = 'yellow';
                $scope.maxColor = 'green';
                $scope.dataRange = $scope.options.max - $scope.options.min;
            }

        }],
        templateUrl: 'js/angular/common/directives/pivotTable/pivotTable.html'
    }
})
.filter('abs', function () {
  return function(val) {
    return Math.abs(val);
  }
});
