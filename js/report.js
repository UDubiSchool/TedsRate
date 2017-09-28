'use strict';
var app = angular.module('report', ['ui.bootstrap', 'bootstrapLightbox', 'ui.validate', 'ngCookies', 'teds.models', 'teds.directives.dropdown', 'teds.directives.filterList', 'teds.directives.pivotTable', 'AngularPrint', 'toArray', 'nvd3']);

app.controller('reportCtrl', ['$scope', '$http', '$q', '$uibModal', 'Lightbox', function($scope, $http, $q, $uibModal, Lightbox) {

    $scope.pivotOptions = {
        colorize: true,
        min: 1,
        max: 5,
        minColor: 'yellow',
        maxColor: 'green'
    };

    $scope.sampleChartOptions = {
        chart: {
            type: 'discreteBarChart',
            height: 300,
            width:400,
            margin : {
                top: 20,
                right: 20,
                bottom: 50,
                left: 55
            },
            x: function(d){return d.label;},
            y: function(d){return d.value;},
            showValues: true,
            tooltips: false,
            valueFormat: function(d){
                return d3.format(',.0f')(d);
            },
            duration: 500,
            xAxis: {
                axisLabel: 'Answer'
            },
            yAxis: {
                axisLabel: 'Count',
                axisLabelDistance: -15,
                margin: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            },
            discretebar: {
                width: 100,
                height: 100,
                margin: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            }
        }
    };

    $scope.reportData = reportData;
    $scope.passback = {};

}]);


app.config(function(LightboxProvider) {
  //
    LightboxProvider.templateUrl = 'partials/lightbox.html';
    LightboxProvider.fullScreenMode = true;
});
app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});