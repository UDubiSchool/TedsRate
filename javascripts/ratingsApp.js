var app = angular.module('dawgCoffee')
   .service("rating",['$http', function($http) {

      var ratings = [];

      this.get = function() {
        $http.get("../models/admin_rp_model.php").then(function (response) {
          ratings = response.data;
        });
        console.log(ratings);
        return ratings;
      }

   }])

   .controller('MainController', ['$scope', '$http', 'rating' , function($scope, $http, $rating) {

      $scope.ratings = $rating.get();

   }])

   // .config(function($stateProvider, $urlRouterProvider, $locationProvider) {
   //   //
   //   // For any unmatched url, redirect to /state1
   //   $urlRouterProvider.otherwise("/");
   //   // $locationProvider.html5Mode(true);

   //   //
   //   // Now set up the states
   //   $stateProvider
   //    .state('Order', {
   //      url: "/orders",
   //      templateUrl: "partials/order.html"
   //    })
   //    .state('Cart', {
   //      url: "/orders/cart",
   //      templateUrl: "partials/cart.html"
   //    })
   //    .state('Details', {
   //      url: "/orders/:beanId",
   //      templateUrl: "partials/details.html",
   //      controller : function($scope, $stateParams) {
   //          $scope.beanId = $stateParams.beanId;

   //       }
   //    })
   //     .state('Home', {
   //       url: "/:jumpPoint",
   //       templateUrl: "partials/home.html",
   //       controller : function($scope, $stateParams, $location, $anchorScroll) {
   //          $location.hash($stateParams.jumpPoint);
   //          $anchorScroll();
   //          $location.hash('');

   //       }
   //     })

    });

  // configure html5 to get links working on jsfiddle
