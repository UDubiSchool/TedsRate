var app = angular.module('ratingsApp', [])
   .controller('MainController', ['$scope', '$http' , function($scope, $http) {

      $http.get("models/admin_rp_model.php").then(function(response) {
        // var temp = JSON.parse(response.data);
        $scope.ratings = response.data;
        console.log(response);
      });
      console.log($scope.ratings);

   }]);
