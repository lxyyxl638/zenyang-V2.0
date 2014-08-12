'use strict';

/* Controllers */

var adminControllers = angular.module('adminControllers',[]);

adminControllers.controller('testCtrl',['$scope','$http',
	function($scope,$http){

		$scope.postTest = function(p){
			var url = '../../CI/index.php/'+p.APIURL+'/format/json/';
			var content = JSON.parse(p.postContent);
			console.log(content);
			$http({
				method: 'POST',
				url: url,
				data: content,
			}).success(function(response){
                $scope.postResult = JSON.stringify(response);
            }).error(function(response){
                alert("Error!");
            })
		};

		$scope.getTest = function(g) {
			$http.get('../../CI/index.php/'+g.APIURL+'/format/json/').success(function(response){
				$scope.getResult = JSON.stringify(response);
			}).error(function(response){
				alert("Error!");
			})
		};

	}]);
