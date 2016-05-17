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

            // $scope.selectRow = function(item){
            //     console.log($scope.tedsPassback);
            //     $scope.selected.row = item;
            //     $scope.selected.selected = true;
            //     $scope.tedsPassback.row = item;
            // }

            // $scope.selectCol = function(item){
            //     $scope.selected.column = item;
            //     $scope.selected.selected = true;
            //     $scope.tedsPassback.column = item;
            // }

            $scope.selectCell = function(cell, columnID, rowID){
                console.log(rowID);
                $scope.selected.column = $scope.tedsData.columns[columnID];
                $scope.tedsPassback.column = $scope.tedsData.columns[columnID];
                $scope.selected.row = $scope.tedsData.rows[rowID];
                $scope.tedsPassback.row = $scope.tedsData.rows[rowID];
                $scope.selected.cell = cell;
                $scope.tedsPassback.cell = cell;
                $scope.selected.selected = true;
            }

            $scope.unsetSelected = function() {
                $scope.selected = {
                    selected: false
                };
                delete $scope.tedsPassback.column;
                delete $scope.tedsPassback.row;
                delete $scope.tedsPassback.cell;
            }

        }],
        templateUrl: 'js/angular/common/directives/pivotTable/pivotTable.html'
    }
})
.filter('abs', function () {
  return function(val) {
    return Math.abs(val);
  }
}).directive('exportToCsv',function(){
    return {
        restrict: 'A',
        link: function (scope, element, attrs) {
            var el = element[0];
            element.bind('click', function(e){
                var table = e.target.nextElementSibling;
                var csvString = '';
                for(var i=0; i<table.rows.length;i++){
                    var rowData = table.rows[i].cells;
                    console.log(rowData);
                    for(var j=0; j<rowData.length;j++){
                        if(i == 0 || j == 0) {
                            csvString = csvString + rowData[j].innerHTML + ",";
                        } else {
                            csvString = csvString + rowData[j].innerText.replace(/[\n\r]/g, '') + ",";
                        }
                        if(j == (rowData.length - 1)) {
                            csvString = csvString.substring(0,csvString.length - 1);
                            csvString = csvString + "\n";
                        }
                    }

                }
                csvString = csvString.substring(0, csvString.length - 1);
                var a = $('<a/>', {
                    style:'display:none',
                    href:'data:application/octet-stream;base64,'+btoa(csvString),
                    download:'pivotTableStats.csv'
                }).appendTo('body')
                a[0].click()
                a.remove();
            });
        }
    }
});