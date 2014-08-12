'use strict';

/* Controllers */

var infoControllers = angular.module('infoControllers',[]);

infoControllers.controller('infoCtrl',['$scope','$http',
	function($scope,$http){
		$scope.alert={};
		$scope.alert.genderRequire=false;
		$scope.alert.occupationRequire=false;
		$scope.alert.bioLength=false;
		$scope.alert.bioInvalid=false;
		$scope.basicSubmit = function(basic){
			var url = '../CI/index.php/signup/info/format/json/';
			if(typeof basic.occupation!==undefined){
				var job = basic.occupation;
			}
			$http({
				method: 'POST',
				url: url,
				data: basic,
			}).success(function(response){
                if(response.state == "success")
                {
                	if(job=='S'){
						window.location.replace("#/student");
					}
					else if(job=='W'){
						window.location.replace("#/non-student");
					}
                }
                else if(response.state == "fail")
                {
                	$scope.alert.genderRequire=false;
					$scope.alert.occupationRequire=false;
					$scope.alert.bioLength=false;
					$scope.alert.bioInvalid=false;
                	if(response.detail=="Unlogin"){
                		window.location.replace("/zenyang");
                	}
                	else if(response.detail=="genderRequire"){
                		$scope.alert.genderRequire=true;
                	}
                	else if(response.detail=="occupationRequire"){
                		$scope.alert.occupationRequire=true;
                	}
                	else if(response.detail=="bioLength"){
						$scope.alert.bioLength=true;
                	}
                	else if(response.detail=="bioInvalid"){
                		$scope.alert.bioInvalid=true;
                	}
                }
            }).error(function(response){
                alert("Error!");
            })
		};
	}]);

infoControllers.controller('provinceCtrl',['$scope','$http','dataFactory',
	function($scope,$http,dataFactory){

		dataFactory.get('province.json').then(function(data){
			$scope.items=data;
		});
		$scope.name="";
		$scope.onItemSelected=function(){
			$scope.submit.province = $scope.name;
			console.log('selected='+$scope.name);
	}

	}]);

infoControllers.controller('collegeCtrl',['$scope','$http','dataFactory',
	function($scope,$http,dataFactory){

		dataFactory.get('college.json').then(function(data){
			$scope.items=data;
		});
		$scope.name="";
		$scope.onItemSelected=function(){
			$scope.submit.college = $scope.name;
			console.log('selected='+$scope.name);
	}
	
	}]);

infoControllers.controller('majorCtrl',['$scope','$http','dataFactory',
	function($scope,$http,dataFactory){

		dataFactory.get('major.json').then(function(data){
			$scope.items=data;
		});
		$scope.name="";
		$scope.onItemSelected=function(){
			$scope.submit.major = $scope.name;
			console.log('selected='+$scope.name);
	}
	
	}]);

infoControllers.controller('yearCtrl',['$scope','$http','dataFactory',
	function($scope,$http,dataFactory){
		$scope.items=[];
		for(var i=2000; i<2100; i++){
			$scope.items[i-2000]={};
			$scope.items[i-2000].name=i;
		}

		$scope.name="";
		$scope.onItemSelected=function(){
			$scope.submit.year = $scope.name;
			console.log('selected='+$scope.name);
	}
	
	}]);

infoControllers.controller('companyCtrl',['$scope','$http','dataFactory',
	function($scope,$http,dataFactory){

		dataFactory.get('major.json').then(function(data){
			$scope.items=data;
		});
		$scope.name="";
		$scope.onItemSelected=function(){
			$scope.submit.company = $scope.name;
			console.log('selected='+$scope.name);
	}
	
	}]);

infoControllers.controller('positionCtrl',['$scope','$http','dataFactory',
	function($scope,$http,dataFactory){

		dataFactory.get('major.json').then(function(data){
			$scope.items=data;
		});
		$scope.name="";
		$scope.onItemSelected=function(){
			$scope.submit.position = $scope.name;
			console.log('selected='+$scope.name);
	}
	
	}]);

infoControllers.controller('studentCtrl',['$scope','$http',
	function($scope,$http,dataFactory){
		$scope.submit = {};
		$scope.studentSubmit = function(s){
			var url = '../CI/index.php/signup/more/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: s,
			}).success(function(response){
                if(response.state == "success")
                {
					window.location.replace("../home");                	
                }
            }).error(function(response){
                alert("Error!");
            })
		};
	}]);


infoControllers.controller('nonstudentCtrl',['$scope','$http',
	function($scope,$http){
		$scope.submit = {};
		$scope.nonstudentSubmit = function(n){
			var url = '../CI/index.php/signup/more/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: n ,
			}).success(function(response){
                if(response.state == "success")
                {
					window.location.replace("../home");
                }
            }).error(function(response){
                alert("Error!");
            })
		};
	}]);