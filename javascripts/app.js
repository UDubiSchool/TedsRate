var app = angular.module('ratingsApp', [])
   .service("rating",['$http', function($http) {

      // var ratings = [];

      this.get = function() {
        var ratings = {};
        $http.get("models/admin_rp_model.php").success(function(data) {
          ratings = data;
          console.log(data);
        });

        return ratings;
      }

   }])

   .controller('MainController', ['$scope', '$http', 'rating' , function($scope, $http, $rating) {

      $scope.ratings = $rating.get();
      console.log("scope ratings" + $scope.ratings);

   }]);
