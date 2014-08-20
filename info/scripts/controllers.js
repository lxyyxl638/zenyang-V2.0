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


infoControllers.controller('studentCtrl',['$scope','$http',
	function($scope,$http){
		$scope.collegeextra = true;
		$scope.majorextra = true;

		$scope.alert = {};
		var alertMap = [
					"yearRequire",
					"yearInvalid",
					"collegeRequire",
					"majorRequire"
		]

		$scope.alert.yearRequire = false;
		$scope.alert.yearInvalid = false;
		$scope.alert.collegeRequire = false;
		$scope.alert.majorRequire = false;

		$scope.onselectCollege = function($item, $model, $label){
			$scope.collegeextra = false;
		}

		$scope.onselectMajor = function($item, $model, $label){
			$scope.majorextra = false;
		}

		$scope.getCollege = function($viewValue){
			$scope.collegeextra = true;
			var url = "../CI/index.php/signup/collegelist/format/json";
			var val = {};
			val.college = $viewValue;
			return $http({
				method: 'POST',
				url: url,
				data: val,
			}).then(function(res){
				var colleges = [];
				angular.forEach(res.data,function(item){
					colleges.push(item.college);
				});
				return colleges;
			});
		};

		$scope.getMajor = function($viewValue){
			$scope.majorextra = true;
			var url = "../CI/index.php/signup/majorlist/format/json";
			var val = {};
			val.major = $viewValue;
			return $http({
				method: 'POST',
				url: url,
				data: val,
			}).then(function(res){
				var majors = [];
				angular.forEach(res.data,function(item){
					majors.push(item.major);
				});
				return majors;
			});
		};

		$scope.years=[];
		for(var i=1900; i<2100; i++){
			$scope.years.push(i);
		}

		$scope.studentSubmit = function(s){
			var url = '../CI/index.php/signup/more/format/json/';
			s.collegeextra = $scope.collegeextra;
			s.majorextra = $scope.majorextra;
			if(s.collegeextra){
				s.collegeabbr = pinyin.getFullChars(s.college);
			}
			if(s.majorextra){
				s.majorabbr = pinyin.getFullChars(s.major);
			}
			$http({
				method: 'POST',
				url: url,
				data: s,
			}).success(function(response){
                if(response.state == "success")
                {
					window.location.replace("#/tag");                	
                }
                else if (response.state == "fail"){
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


infoControllers.controller('nonstudentCtrl',['$scope','$http',
	function($scope,$http){
		$scope.companyextra = true;
		$scope.positionextra = true;

		$scope.alert = {};
		var alertMap = [
					"companyRequire",
					"positionRequire"
		]

		$scope.alert.yearRequire = false;
		$scope.alert.yearInvalid = false;
		$scope.alert.collegeRequire = false;
		$scope.alert.majorRequire = false;

		$scope.onselectCompany = function($item, $model, $label){
			$scope.companyextra = false;
		}

		$scope.onselectPosition = function($item, $model, $label){
			$scope.positionextra = false;
		}

		
		$scope.getCompany = function($viewValue){
			$scope.companyextra = true;
			var url = "../CI/index.php/signup/companylist/format/json";
			var val = {};
			val.company = $viewValue;
			return $http({
				method: 'POST',
				url: url,
				data: val,
			}).then(function(res){
				var companys = [];
				angular.forEach(res.data,function(item){
					companys.push(item.company);
				});
				return companys;
			});
		};

		$scope.getPosition = function($viewValue){
			$scope.positionextra = true;
			var url = "../CI/index.php/signup/positionlist/format/json";
			var val = {};
			val.position = $viewValue;
			return $http({
				method: 'POST',
				url: url,
				data: val,
			}).then(function(res){
				var positions = [];
				angular.forEach(res.data,function(item){
					positions.push(item.position);
				});
				return positions;
			});
		};


		$scope.nonstudentSubmit = function(n){
			var url = '../CI/index.php/signup/more/format/json/';
			n.companyextra = $scope.companyextra;
			n.positionextra = $scope.positionextra;
			if(n.companyextra){
				n.companyabbr = pinyin.getFullChars(n.company);
			}
			if(n.positionextra){
				n.positionabbr = pinyin.getFullChars(n.position);
			}
			$http({
				method: 'POST',
				url: url,
				data: n,
			}).success(function(response){
                if(response.state == "success")
                {
					window.location.replace("#/tag");                	
                }
                else if (response.state == "fail"){
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


infoControllers.controller('picktagCtrl', ['$scope','$http',
 function($scope,$http){
 	$scope.tagSelected = [];
 	$scope.alert = {};
 	$scope.alert.tagNum = false;

 	$scope.tagPush = function(index){
 		$scope.tagSelected.push($scope.tagList[index]);
 		console.log($scope.tagSelected);
 	}

 	$scope.tagCancel = function(index){
 		for (var i=0; i<$scope.tagSelected.length; i++){
 			if ($scope.tagSelected[i].tagid == $scope.tagList[index].tagid){
 				$scope.tagSelected.splice(i,1);
 			}
 		}
 	}

 	$http.get("../CI/index.php/tag_system/tag_show/format/json")
 	.success(function(data){
 		$scope.tagList = data;
 	})

	$scope.finishSubmit = function(){
		var tagpost = {};
		if ($scope.tagSelected.length < 3){
			$scope.alert.tagNum = true;
		}
		else{
			tagpost.tag = $scope.tagSelected;
			$http({
				method:'POST',
				url:"../CI/index.php/tag_system/user_set_tag/format/json",
				data:tagpost,
			}).then(function(response){
				if(response.data.state == 'success'){
					window.location.replace("../home");
				}
			})
		}
	}
}]);