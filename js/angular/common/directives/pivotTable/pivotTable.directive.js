'use strict';
var app = angular.module('teds.directives.pivotTable', ['AngularPrint'])
.constant('pathConst',{
    pathToDir: 'js/angular/common/directives/pivotTable/'
})
.directive('tedsPivotTable', function(){
    return {
        restrict: 'E',
        transclude: true,
        scope: {
            tedsData: '=',
            tedsOptions: '=',
            tedsPassback: '='
        },
        controller: ['$scope', '$timeout', function($scope, $timeout) {
            // console.log($scope.tedsData);
            $scope.options = $scope.tedsOptions;
            $scope.data = $scope.tedsData;
            // $scope.passback = $scope.tedsPassback;
            $scope.selected = {};

            if ($scope.options.colorize) {
                $scope.minColor = 'yellow';
                $scope.maxColor = 'green';
                $scope.dataRange = $scope.options.max - $scope.options.min;
            }
            $scope.sort = {
                id: '',
                stat: '',
                reverse: false
            };

            $scope.rowOrder = function(row) {
                var id = $scope.sort.id;
                var stat = $scope.sort.stat;

                if (row !== '' && row !== null && row !== undefined) {
                    if(id == '' || stat == '') {
                        return undefined;
                    } else if (row.cells[id][stat] == undefined || row.cells[id][stat] == null) {
                        return -1;
                    } else {
                        return row.cells[id][stat];
                    }
                } else {
                    return undefined
                }
            }

            $scope.selectRow = function(item){
                console.log($scope.tedsPassback);
                $scope.selected.row = item;
                $scope.selected.selected = true;
                $scope.tedsPassback.row = item;
            }

            $scope.selectCol = function(item){
                $scope.selected.column = item;
                $scope.selected.selected = true;
                $scope.tedsPassback.column = item;
            }

            $scope.unsetSelected = function() {
                $scope.selected = {
                    selected: false
                };
                delete $scope.passback;
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
