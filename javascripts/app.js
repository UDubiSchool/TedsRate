var app = angular.module('ratingsApp', [])
   .service("rating",['$http', function($http) {

      this.ratings = [];

      this.get = function() {
        // var ratings = [];
        $http.get("models/admin_rp_model.php").then(function(response) {
          // var temp = JSON.parse(response.data);
          this.ratings = response;
          console.log(response);
        });
        console.log(this.ratings);
        return this.ratings;
      }

   }])

   .controller('MainController', ['$scope', '$http', 'rating' , function($scope, $http, $rating) {

      $http.get("models/admin_rp_model.php").then(function(response) {
        // var temp = JSON.parse(response.data);
        $scope.ratings = response;
        console.log(response);
      });
      console.log($scope.ratings);

   }]);
