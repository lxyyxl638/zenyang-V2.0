'use strict';

/* Controllers */

var signControllers = angular.module('signControllers',[]);

signControllers.controller('signupCtrl',['$scope','$http',
	function($scope,$http){
        $scope.alert={};
        var alertMap=[
                "emailInvalid",
                "emailOccupied",
                "emailRequire",
                "passwordLength",
                "passwordInvalid",
                "passwordRequire",
                "firstnameLength",
                "firstnameRequire",
                "lastnameLength",
                "lastnameRequire",
                "firstnameInvalid",
                "lastnameInvalid",
                "CDKInvalid"]

        $scope.alert.emailInvalid=false;
        $scope.alert.emailOccupied=false;
        $scope.alert.emailRequire=false;
        $scope.alert.passwordLength=false;
        $scope.alert.passwordInvalid=false;
        $scope.alert.passwordRequire=false;
        $scope.alert.firstnameLength=false;
        $scope.alert.firstnameRequire=false;
        $scope.alert.lastnameLength=false;
        $scope.alert.lastnameRequire=false;
        $scope.alert.firstnameInvalid=false;
        $scope.alert.lastnameInvalid=false;
        $scope.alert.CDKInvalid=false;


		$scope.register = function(user){
			var url = 'CI/index.php/signup/basic/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: user,
			}).success(function(response){
                if(response.state == "success")
                {
                	var url = 'CI/index.php/log/login/format/json/';
					$http({
						method: 'POST',
						url: url,
						data: user,
					}).success(function(response){
                		window.location.replace("/zenyang/info");
                	})
                }
                else if(response.state == "fail")
                {
                    for (var al in alertMap){
                        if (response.detail == alertMap[al])
                            $scope.alert[alertMap[al]] = true;
                        else
                            $scope.alert[alertMap[al]] = false;
                    }
                }
            }).error(function(response){
                alert("Error!");
            })
		};
	}]);

signControllers.controller('signinCtrl',['$scope','$http',
	function($scope,$http){
        $scope.alert={};
        $scope.alert.emailNotExist = false;
        $scope.alert.passwordWrong = false;
        $scope.alert.wait15Min = false;
        $scope.alert.passwordRequire = false;
		$scope.login = function(user){
			var url = 'CI/index.php/log/login/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: user,
			}).success(function(response){
                if (response.state == "success")
                {
                	window.location.replace("/zenyang/home");
                }
                else if(response.state == "root")
                {
                	alert("root!");
                }
                else if(response.state == "fail")
                {
                    $scope.alert.emailNotExist = false;
                    $scope.alert.passwordWrong = false;
                    $scope.alert.wait15Min = false;
                    $scope.alert.passwordRequire = false;
                    if(response.detail == "emailNotExist"){
                        $scope.alert.emailNotExist = true;
                    }
                    else if(response.detail == "passwordWrong"){
                        $scope.alert.passwordWrong = true;
                    }
                    else if(response.detail == "wait15Min"){
                        $scope.alert.wait15Min = true;
                    }
                    else if(response.detail == "passwordRequire"){
                        $scope.alert.passwordRequire = true;
                    }
                }
            }).error(function(response){
                alert("Error!");
            })
		};
	}]);
