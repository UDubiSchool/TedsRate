var app = angular.module('ratingsApp', [])
   .service("rating",['$http', function($http) {

      var ratings = [];

      this.get = function() {
        // var ratings = [];
        $http.get("models/admin_rp_model.php").success(function(response) {
          // var temp = JSON.parse(response.data);
          ratings = response;
          console.log(response);
        });
        console.log(ratings);
        return ratings;
      }

   }])

   .controller('MainController', ['$scope', '$http', 'rating' , function($scope, $http, $rating) {

      $scope.ratings = $rating.get();
      console.log($scope.ratings);

   }]);
