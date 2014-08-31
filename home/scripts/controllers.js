'use strict';

/* Controllers */

var mainControllers = angular.module('mainControllers',[]);

mainControllers.controller('navCtrl',['$scope', '$interval','publicFactory','$http','notifyFactory','$location','$modal','msgFactory',
	function($scope,$interval,publicFactory,$http,notifyFactory,$location,$modal,msgFactory){
		
		$scope.updates = [];
		$http.get('../CI/index.php/update/message/format/json').success(function(response){
			$scope.updates = response;
			});


		// Notification Defines
		$scope.notify = {};
		$scope.notify.show = false;

		$scope.msg = {};
		$scope.msg.show = false;
		$scope.msg.num = "";

		$scope.status = {
    		isopen: false
  		};

  		$scope.notifyMsg = [];
  		$scope.notifyToggle = false;

  		$scope.toggleDropdown = function($event) {
    		$event.preventDefault();
    		$scope.status.isopen = !$scope.status.isopen;
  		};

		var checkNotifyRoutine = function(){
			notifyFactory.checkNew().then(function(response){
				if (typeof response.num !== undefined){
					if(response.num == 0){
						$scope.notify.show = false;
						$scope.notify.totalNum = 0;
					}
					else{
						$scope.notify.show = true;
						$scope.notify.totalNum = response.num;
					}
				}
			})
			msgFactory.checkNew().then(function(response){
				if (typeof response.sum !== undefined){
					if (response.sum == 0){
						$scope.msg.show = false;
					}
					else{
						$scope.msg.show = true;
						$scope.msg.num = response.sum;
					}
				}
			})
		};

		// Notification dropdown

		$scope.getNotifyMsg = function(){
			if($scope.notifyToggle){
				notifyFactory.getNotify().then(function(){
					$scope.notifyMsg.length = 0;
					var nmsg = notifyFactory.notifyShow();
					for (var item in nmsg){
						var temp = {};
						if(nmsg[item].type == 1){
							temp.disp = nmsg[item].realname + " 回答了你的提问 " + nmsg[item].title.slice(0,5) + "...";
							temp.link = "#/question/"+nmsg[item].qid;
						}
						else if(nmsg[item].type == 2){
							temp.disp = nmsg[item].realname + " 回答了你关注的问题 " + nmsg[item].title.slice(0,5) + "...";
							temp.link = "#/question/"+nmsg[item].qid;
						}
						else if(nmsg[item].type == 3){
							temp.disp = nmsg[item].realname + " 膜拜了你在问题 " + nmsg[item].title.slice(0,5) + "... 中的回答";
							temp.link = "#/question/"+nmsg[item].qid;
						}

						$scope.notifyMsg.push(temp);
					}
				})
			}
			$scope.notifyToggle = !$scope.notifyToggle;
		}


		// Init user basic infomation
		publicFactory.initMe().then(function(){
			$scope.realname = publicFactory.getSelfname();
			$scope.pic = publicFactory.getSelfPicS();
			$scope.uid = publicFactory.getSelfid();
		});

		// Notification routine
		checkNotifyRoutine();
		var checkNotify = $interval(checkNotifyRoutine,5000,0,false);

		// Logout function
		$scope.logout = function(){
			$http.get('../CI/index.php/log/logout/format/json').success(function(response){
				if(response.state == "success")
				{
					window.location.replace("../");
				}
			});
		}

		// nav bar active effect
		$scope.nav = {};
		$scope.nav.atHome = false;
		$scope.nav.atQuestion = false;
		$scope.nav.atNotify = false;

		var activeCheck = function(){
			var path = $location.path();
			if (path == '/'){
				$scope.nav.atHome = true;
				$scope.nav.atQuestion = false;
				$scope.nav.atNotify = false;
			}
			else if (path == '/open-question'){
				$scope.nav.atHome = false;
				$scope.nav.atQuestion = true;
				$scope.nav.atNotify = false;
			}
			else if (path =='/notification'){
				$scope.nav.atHome = false;
				$scope.nav.atQuestion = false;
				$scope.nav.atNotify = true;
			}
			else{
				$scope.nav.atHome = false;
				$scope.nav.atQuestion = false;
				$scope.nav.atNotify = false;
			}
		}

		activeCheck();
		$scope.$on('$routeChangeSuccess', activeCheck);

		// add question modal
  		$scope.open = function (size) {

		    var modalInstance = $modal.open({
		      templateUrl: 'myModalContent.html',
		      controller: ModalInstanceCtrl,
		      size: size,
		      resolve: {
		        }
		    });

		    modalInstance.result.then(function () {
		      console.log('Modal dismissed at: ' + new Date());
		    });
		  
	  	};

	  	$scope.adviseopen = function(size){
	  		var modalInstance = $modal.open({
		      templateUrl: 'advise.html',
		      controller: ModalInstanceCtrl,
		      size: size,
		      resolve: {
		        }
		    });

		    modalInstance.result.then(function () {
		      console.log('Modal dismissed at: ' + new Date());
		    });
	  	}

	  	var ModalInstanceCtrl = function ($scope, $modalInstance) {

	  	  $scope.alert = {};
	  	  $scope.alert.timeInterval = false;
	  	  $scope.alert.titleRequire = false;
	  	  $scope.alert.titleLength = false;
	  	  $scope.alert.contentLength = false;
	  	  $scope.alert.unlogin = false;
	  	  $scope.alert.tagNum = false;
	  	  $scope.tagSelected = [];
	  	  $scope.tag = {};

	  	  $scope.postAdvice = function(a){
			console.log("THIS");
			var url = '../CI/index.php/letter/letter_send/format/json/';
			a.uid="1";
			$http({
				method: 'POST',
				url: url,
				data: a,
			}).success(function(response){
                if(response.state == "success")
                {
                	window.location.reload();
                }
            }).error(function(response){
                alert("Error!");
            })
		};

		  $scope.askquestion = function(q){
			var url = '../CI/index.php/qa_center/question_ask/format/json/';
			q.tag = $scope.tagSelected;
			if (q.tag.length == 0 || q.tag.length>5){
				$scope.alert.tagNum = true;
			}
			else{
				$http({
					method: 'POST',
					url: url,
					data: q,
				}).success(function(response){
	                if (response.state == "success")
	                {
	                	$modalInstance.close();
	                	window.location.replace("../home/#/question/"+response.qid);
	                }
	                else if (response.state == "fail")
	                {
	                	$scope.alert.timeInterval = false;
				  	    $scope.alert.titleRequire = false;
				  	    $scope.alert.titleLength = false;
				  	    $scope.alert.contentLength = false;
				  	    $scope.alert.unlogin = false;
				  	    $scope.alert.tagNum = false;
	                	if (response.detail == "timeInterval"){
	                		$scope.alert.timeInterval = true;
	                	}
	                	else if (response.detail == "titleRequire"){
	                		$scope.alert.titleRequire = true;
	                	}
	                	else if (response.detail == "titleLength"){
	                		$scope.alert.titleLength = true;
	                	}
	                	else if (response.detail == "contentLength"){
	                		$scope.alert.contentLength = true;
	                	}
	                }
	            }).error(function(response){
	                alert("Error!");
	            })
	        }
		};

		// add tags when post a question

		$scope.onselectTag = function($item, $model, $label){
			if ($item.tagid == 0){
				var temp = {};
				temp.tagname = $item.tagValue;
				temp.tagid = $item.tagid;
				temp.tagabbr = pinyin.getFullChars(temp.tagname);
				$scope.tagSelected.push(temp);
			}
			else{
				$item.tagabbr = pinyin.getFullChars($item.tagname);
				$scope.tagSelected.push($item);
			}
			$scope.tag.keyword = "";
		}

		$scope.getTags = function($viewValue){
			var val = {};
			var url = "../CI/index.php/tag_system/tag_search/format/json";
			val.keyword = $viewValue;
			return $http({
				method:'POST',
				url:url,
				data:val,
			}).then(function(res){
				var Results = res.data;
				if(Results.length == 0){
					var temp = {};
					temp.tagname = "添加标签 " + $viewValue;
					temp.tagValue = $viewValue;
					temp.tagid = 0; 
					Results.push(temp);
				}

				return Results;
			})
		}

		  $scope.cancel = function () {
		    $modalInstance.dismiss('cancel');
		  };
		};

		$scope.pathRecord = {};
		$scope.pathRecord.currentPath = $location.path();
		$scope.pathRecord.lastPath = $scope.pathRecord.currentPath;

		$scope.peoplePathRecord = {};
		$scope.peoplePathRecord.currentPath = $location.path();
		$scope.peoplePathRecord.lastPath = $location.path();

		$scope.search = {};
		$scope.search.keyword = "";
		$scope.search.viewValue = "";

		// Search Function
		$scope.onselectSearch = function($item, $model, $label){
			if($item.type == "user"){
				window.location.assign("#/people/"+$item.id);
			}	
			else if ($item.type == "question"){
				window.location.assign("#/question/"+$item.id);
			}
			else if ($item.type == "tag"){
				window.location.assign("#/tag/"+$item.id);
			}
			else if ($item.type == "more"){
				window.location.assign("#/search");
			}
			$scope.search.keyword = "";
		}

		$scope.getSearch = function($viewValue){
			$scope.search.viewValue = $viewValue;
			var url = "../CI/index.php/search_system/search/3/format/json";
			var val = {};
			val.keyword = $viewValue;
			return $http({
				method: 'POST',
				url: url,
				data: val,
			}).then(function(res){
				var Results = [];
				var question = res.data.question;
				var user = res.data.user;
				var tag = res.data.tag;

				angular.forEach(user,function(u){
					var temp = {};
					temp.disp = u.realname + "(用户)";
					temp.id = u.uid;
					temp.type = 'user';
					Results.push(temp);
				});

				angular.forEach(tag,function(t){
					var temp = {};
					temp.disp = t.tagname + "(话题)";
					temp.id = t.tagid;
					temp.type = 'tag';
					Results.push(temp);
				})

				angular.forEach(question,function(q){
					var temp = {};
					if (q.title.length<16){
						temp.disp = q.title;
					}
					else{
						temp.disp = q.title.slice(0,14) + "...";
					}
					temp.id = q.id;
					temp.type = 'question';
					Results.push(temp);
				});
				

				// tag to be added

				if(Results.length == 0){
					var temp = {};
					temp.disp = "没有找到相关的结果...";
					temp.id = 'none';
					temp.type = 'none';
					Results.push(temp);
				}
				else{
					var temp = {};
					temp.disp = "查看更多...";
					temp.type = 'more';
					temp.id = 'more';
					Results.push(temp);
				}

				return Results;
			});
		};

	}]);


mainControllers.controller('searchCtrl', ['$scope','$http',
	function($scope,$http){
		var postData = {};
		postData.keyword = $scope.search.viewValue;
		postData.limit = 100;
		postData.offset = 0;

		if (postData.keyword != ""){
			var urlQ = '../CI/index.php/search_system/search_question/format/json';
				$http({
					method: 'POST',
					url:urlQ,
					data:postData,
				}).success(function(response){
	                $scope.questionResult = response;
	            }).error(function(response){
	                alert("Error!");
	            })


			var urlU = '../CI/index.php/search_system/search_user/format/json/';
				$http({
					method: 'POST',
					url: urlU,
					data: postData,
				}).success(function(response){
	                $scope.userResult = response;
	            }).error(function(response){
	                alert("Error!");
	            })

	        var urlT = '../CI/index.php/search_system/search_tag/format/json/';
				$http({
					method: 'POST',
					url: urlT,
					data: postData,
				}).success(function(response){
	                $scope.tagResult = response;
	            }).error(function(response){
	                alert("Error!");
	            })

	        }

}]);

mainControllers.controller('mainCtrl',['$scope','$http','homeFeedFactory','publicFactory',
	function($scope,$http,homeFeedFactory,publicFactory){
		$scope.tagTAL = [];
		$scope.allTAL = [];
		$scope.myuid = publicFactory.getSelfid();
		$scope.viewmoreTag = false;
		$scope.viewmoreAll = false;
		var limit = 10;

		var updateTagTAL = function(){
			homeFeedFactory.getTagTAL().then(function(){
				var oldLength = $scope.tagTAL.length;
				$scope.tagTAL = homeFeedFactory.tagTopAnswerList();
				var newLength = $scope.tagTAL.length;
				if ((newLength - oldLength) < limit){
					$scope.viewmoreTag = false;
				}
				else{
					$scope.viewmoreTag = true;
				}
			});
		}

		var updateAllTAL = function(){
			homeFeedFactory.getAllTAL().then(function(){
				var oldLength = $scope.allTAL.length;
				$scope.allTAL = homeFeedFactory.allTopAnswerList();
				var newLength = $scope.allTAL.length;
				if ((newLength - oldLength) < limit){
					$scope.viewmoreAll = false;
				}
				else{
					$scope.viewmoreAll = true;
				}
			});
		}

		$scope.updateTagTAL = updateTagTAL;
		$scope.updateAllTAL = updateAllTAL;

		updateTagTAL();
		updateAllTAL();

		$scope.afollow = function(id,$index){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.allTAL[$index].follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.allTAL[$index].follow = 'Y';
				}
			});
		}

		$scope.tfollow = function(id,$index){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.tagTAL[$index].follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.tagTAL[$index].follow = 'Y';
				}
			});
		}

		$scope.alike = function(qid,aid,$index){

			$http.get('../CI/index.php/qa_center/good/'+qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.allTAL[$index].like = false;
					$scope.allTAL[$index].cancellike = true;
					$scope.allTAL[$index].dislike = false;
					$scope.allTAL[$index].canceldislike = false;
					$scope.allTAL[$index].good = data.good;
					$scope.allTAL[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.allTAL[$index].like = true;
					$scope.allTAL[$index].cancellike = false;
					$scope.allTAL[$index].dislike = true;
					$scope.allTAL[$index].canceldislike = false;
					$scope.allTAL[$index].good = data.good;
					$scope.allTAL[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.allTAL[$index].like = false;
					$scope.allTAL[$index].cancellike = false;
					$scope.allTAL[$index].dislike = false;
					$scope.allTAL[$index].canceldislike = true;
					$scope.allTAL[$index].good = data.good;
					$scope.allTAL[$index].bad = data.bad;
				}
			});
		}

		$scope.adislike = function(qid,aid,$index){
			$http.get('../CI/index.php/qa_center/bad/'+qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.allTAL[$index].like = false;
					$scope.allTAL[$index].cancellike = true;
					$scope.allTAL[$index].dislike = false;
					$scope.allTAL[$index].canceldislike = false;
					$scope.allTAL[$index].good = data.good;
					$scope.allTAL[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.allTAL[$index].like = true;
					$scope.allTAL[$index].cancellike = false;
					$scope.allTAL[$index].dislike = true;
					$scope.allTAL[$index].canceldislike = false;
					$scope.allTAL[$index].good = data.good;
					$scope.allTAL[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.allTAL[$index].like = false;
					$scope.allTAL[$index].cancellike = false;
					$scope.allTAL[$index].dislike = false;
					$scope.allTAL[$index].canceldislike = true;
					$scope.allTAL[$index].good = data.good;
					$scope.allTAL[$index].bad = data.bad;
				}
			});
		}

		$scope.tlike = function(qid,aid,$index){

			$http.get('../CI/index.php/qa_center/good/'+qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.tagTAL[$index].like = false;
					$scope.tagTAL[$index].cancellike = true;
					$scope.tagTAL[$index].dislike = false;
					$scope.tagTAL[$index].canceldislike = false;
					$scope.tagTAL[$index].good = data.good;
					$scope.tagTAL[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.tagTAL[$index].like = true;
					$scope.tagTAL[$index].cancellike = false;
					$scope.tagTAL[$index].dislike = true;
					$scope.tagTAL[$index].canceldislike = false;
					$scope.tagTAL[$index].good = data.good;
					$scope.tagTAL[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.tagTAL[$index].like = false;
					$scope.tagTAL[$index].cancellike = false;
					$scope.tagTAL[$index].dislike = false;
					$scope.tagTAL[$index].canceldislike = true;
					$scope.tagTAL[$index].good = data.good;
					$scope.tagTAL[$index].bad = data.bad;
				}
			});
		}

		$scope.tdislike = function(qid,aid,$index){
			$http.get('../CI/index.php/qa_center/bad/'+qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.tagTAL[$index].like = false;
					$scope.tagTAL[$index].cancellike = true;
					$scope.tagTAL[$index].dislike = false;
					$scope.tagTAL[$index].canceldislike = false;
					$scope.tagTAL[$index].good = data.good;
					$scope.tagTAL[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.tagTAL[$index].like = true;
					$scope.tagTAL[$index].cancellike = false;
					$scope.tagTAL[$index].dislike = true;
					$scope.tagTAL[$index].canceldislike = false;
					$scope.tagTAL[$index].good = data.good;
					$scope.tagTAL[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.tagTAL[$index].like = false;
					$scope.tagTAL[$index].cancellike = false;
					$scope.tagTAL[$index].dislike = false;
					$scope.tagTAL[$index].canceldislike = true;
					$scope.tagTAL[$index].good = data.good;
					$scope.tagTAL[$index].bad = data.bad;
				}
			});
		}


	}]);

mainControllers.controller('peopleCtrl',['$scope','$http','$routeParams','peopleFactory','$location','$modal',
	function($scope,$http,$routeParams,peopleFactory,$location,$modal){
		$scope.uid = $routeParams.uid;
		$scope.uploader = {};
		$scope.uploader.Show = false;
		$scope.send = {};
		$scope.send.Show = false;
		$scope.transfer = {};
		$scope.transfer.uid = $scope.uid;
		$scope.more = {};
		$scope.more.myq = true;
		$scope.more.mya = true;
		$scope.more.myf = true;
		var limit = 3;

		$scope.peoplePathRecord.currentPath = $location.path();
		var renew = false;
		if ($scope.peoplePathRecord.currentPath != $scope.peoplePathRecord.lastPath)
		{
			$scope.peoplePathRecord.lastPath = $scope.peoplePathRecord.currentPath;
			renew = true;
		}
		
		peopleFactory.profileInit($scope.uid,renew).then(function(){
			$scope.isME = {};
			$scope.isME.Show = peopleFactory.isMe();
			$scope.isStudent = peopleFactory.isStudent();
			$scope.gender = peopleFactory.getGender();
			$scope.realname = peopleFactory.getRealname();
			$scope.college = peopleFactory.getCollege();
			$scope.major = peopleFactory.getMajor();
			$scope.company = peopleFactory.getCompany();
			$scope.position = peopleFactory.getPosition();
			$scope.bio = peopleFactory.getBio();
			$scope.imgLoc = peopleFactory.getPic();
		});		

		$scope.myquestion = [];
		$scope.myanswer = [];
		$scope.myfollow = [];

		var updateMyQ = function(){
			peopleFactory.refreshQList($scope.uid,renew).then(function(){
				var oldLength = $scope.myquestion.length;
				$scope.myquestion = peopleFactory.getQlist();
				var newLength = $scope.myquestion.length;
				if ((newLength - oldLength)<limit){
					$scope.more.myq = false;
				}
				else{
					$scope.more.myq = true;
				}
			})
		};

		var updateMyA = function(){
			peopleFactory.refreshAList($scope.uid).then(function(){
				var oldLength = $scope.myanswer.length;
				$scope.myanswer = peopleFactory.getAlist();
				var newLength = $scope.myanswer.length;
				if ((newLength - oldLength)<limit){
					$scope.more.mya = false;
				}
				else{
					$scope.more.mya = true;
				}
			})
		}

		var updateMyF = function(){
			peopleFactory.refreshFList($scope.uid).then(function(){
				var oldLength = $scope.myfollow.length;				
				$scope.myfollow = peopleFactory.getFlist();
				var newLength = $scope.myfollow.length;
				if ((newLength - oldLength)<limit){
					$scope.more.myf = false;
				}
				else{
					$scope.more.myf = true;
				}
			})
		}

		updateMyQ();
		updateMyA();
		updateMyF();

		$scope.updateMyQ = updateMyQ;
		$scope.updateMyA = updateMyA;
		$scope.updateMyF = updateMyF;

		$scope.modifyImg = function(){
			$scope.uploader.Show = !$scope.uploader.Show;
		}

		$scope.sendMsg = function(){
			$scope.send.Show = !$scope.send.Show;
		}

		$scope.modifyTypeFn = function(t){
			$scope.modifyType = false;
			var url = '../CI/index.php/personal_center/modify_profile/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: t,
			}).success(function(response){
                if(response.occupation == 'S'){
                	$scope.isStudent = true;
                }
                else if (response.occupation == 'W'){
                	$scope.isStudent = false;
                }
            }).error(function(response){
                alert("Error!");
            })
		}

		$scope.modifyPlaceFn = function(m){
			$scope.modifyPlace = false;
			var url = '../CI/index.php/personal_center/modify_profile/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: m,
			}).success(function(response){
                $scope.college = response.jobplace;
                $scope.company = response.jobplace;
            }).error(function(response){
                alert("Error!");
            })
		}

		$scope.modifyJobFn = function(j){
			$scope.modifyJob = false;
			var url = '../CI/index.php/personal_center/modify_profile/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: j,
			}).success(function(response){
                $scope.position = response.job;
                $scope.major = response.job;
            }).error(function(response){
                alert("Error!");
            })
		}

		$scope.modifyBioFn = function(b){
			$scope.modifyBio = false;
			var url = '../CI/index.php/personal_center/modify_profile/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: b,
			}).success(function(response){
                $scope.bio = response.bio;
            }).error(function(response){
                alert("Error!");
            })
		}

		$scope.modifyNameFn = function(w){
			$scope.modifyName = false;
			var url = '../CI/index.php/personal_center/modify_profile/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: w,
			}).success(function(response){
                $scope.realname = response.realname;
            }).error(function(response){
                alert("Error!");
            })
		}

		$scope.modifyQuestionOpen = function(size,$index){

	  		var modalInstance = $modal.open({
	  			templateUrl: 'modifyQuestion.html',
	  			controller: ModalInstanceCtrlQ,
	  			size: size,
	  			resolve: {
	  				q_original:function(){
	  					return $scope.myquestion[$index];
	  				}
	  			}
	  		});

	  		modalInstance.result.then(function () {
		      console.log('Modal dismissed at: ' + new Date());
		    });
	  	}

	  	var ModalInstanceCtrlQ = function ($scope, $modalInstance,q_original) {

	  	  $scope.alert = {};
	  	  $scope.alert.timeInterval = false;
	  	  $scope.alert.titleRequire = false;
	  	  $scope.alert.titleLength = false;
	  	  $scope.alert.contentLength = false;
	  	  $scope.alert.unlogin = false;

	  	  $scope.q = q_original;

	  	  $scope.modify={};
	  	  $scope.modify.title = q_original.title;
	  	  $scope.modify.content = q_original.content;

		  $scope.postQuestionModify = function(modify){
		  	modify.qid = $scope.q.id;
			var url = '../CI/index.php/qa_center/question_ask/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: modify,
			}).success(function(response){
                if (response.state == "success")
                {
                	$modalInstance.close();
                	window.location.replace("../home/#/question/"+modify.qid);
                }
                else if (response.state == "fail")
                {
                	$scope.alert.timeInterval = false;
			  	    $scope.alert.titleRequire = false;
			  	    $scope.alert.titleLength = false;
			  	    $scope.alert.contentLength = false;
			  	    $scope.alert.unlogin = false;
                	if (response.detail == "timeInterval"){
                		$scope.alert.timeInterval = true;
                	}
                	else if (response.detail == "titleRequire"){
                		$scope.alert.titleRequire = true;
                	}
                	else if (response.detail == "titleLength"){
                		$scope.alert.titleLength = true;
                	}
                	else if (response.detail == "contentLength"){
                		$scope.alert.contentLength = true;
                	}
                }
            }).error(function(response){
                alert("Error!");
            })
		};

		  $scope.cancel = function () {
		    $modalInstance.dismiss('cancel');
		  };
		};

	}]);

// !!! no limit for now

mainControllers.controller('messageCtrl',['$scope','$http','msgFactory',
	function($scope,$http,msgFactory){
		msgFactory.messageList().then(function(response){
			$scope.msgList = response;
		});

		msgFactory.setRead().then(function(){
			// do nothing
		});
	}]);

mainControllers.controller('talkCtrl',['$scope','$http','$routeParams',
	function($scope,$http,$routeParams){
		$http.get('../CI/index.php/public_function/myinfo/format/json')
		.success(function(data){
			$scope.myuid = data.myuid;
			$scope.myimg = data.location_middle;
			$scope.myrealname = data.myrealname;
		});

		$scope.uid = $routeParams.uid;

		$scope.other = {};
		$http.get('../CI/index.php/public_function/middle_photo/'+$scope.uid+'/format/json')
		.success(function(data){
			$scope.other.img = data.location;
		});
		$http.get('../CI/index.php/public_function/uidinfo/'+$scope.uid+'/format/json')
		.success(function(data){
			$scope.other.realname = data.uidrealname;
		});

		$http.get('../CI/index.php/letter/letter_talk/'+$scope.uid+'/format/json')
		.success(function(data){
			// if (typeof(data) == typeof("string")){
			// 	$scope.myTalk = {};
			// }
			// else{
				$scope.myTalk = data;
			// }
		});


		$scope.postMsg = function(m,id){
			var url = '../CI/index.php/letter/letter_send/format/json/';
			m.uid=id;
			$http({
				method: 'POST',
				url: url,
				data: m,
			}).success(function(response){
                if(response.state == "success")
                {
                	window.location.reload();
                }
            }).error(function(response){
                alert("Error!");
            })
		};
	}]);

mainControllers.controller('settingCtrl',['$scope','$http',
	function($scope,$http){
		$scope.success = false;
		$scope.alert = {};

        var alertMap=[
        		"oldpasswordRequire",
        		"newpasswordRequire",
                "inconformity",
                "newpasswordLength",
                "newpasswordInvalid",
                "passwordWrong"]

        $scope.alert.oldpasswordRequire=false;
        $scope.alert.newpasswordRequire=false;
        $scope.alert.inconformity=false;
        $scope.alert.newpasswordLength=false;
        $scope.alert.newpasswordInvalid=false;
        $scope.alert.passwordWrong=false;

		$scope.modifyPSW = function(psw){
			var url = '../CI/index.php/personal_center/change_password/format/json';
			$http({
				method: 'POST',
				url: url,
				data: psw,
			}).success(function(response){
				if(response.state == "success"){
					$scope.success = true;
					for (var al in alertMap){
						$scope.alert[alertMap[al]] = false;
					}
					$scope.psw.oldpassword = "";
					$scope.psw.newpassword = "";
					$scope.psw.passwordconf = "";
				}
				else if(response.state == "fail"){
					for (var al in alertMap){
                        if (response.detail == alertMap[al])
                            $scope.alert[alertMap[al]] = true;
                        else
                            $scope.alert[alertMap[al]] = false;
                    }
				}
			}).error(function(response){
				console.log(response);
			})
		};


	}]);

mainControllers.controller('questionCtrl',['$scope','$http','$routeParams','aListFactory','$location','publicFactory',
	function($scope,$http,$routeParams,aListFactory,$location,publicFactory){
		$scope.question = {};
		$scope.answers = [];
		$scope.qid = $routeParams.id;
		$scope.myuid = publicFactory.getSelfid();
		$scope.moreanswer = true;
		var limit = 10;

		aListFactory.refreshQuestion($scope.qid).then(function(){
			$scope.question = aListFactory.getQuestion();
			if (typeof $scope.question.tag == 'undefined'){
        		$scope.question.tag = [];
        	}
		});

		if($scope.question.answerdeny == 'Y'){
			$scope.myanswer = "";
			$scope.modifyAnswer = false;
		}

		$scope.alert = {};
		$scope.alert.contentLength = false;
		$scope.alert.tagNum = false;

		$scope.tag = {};

		$scope.beginmodify = function(){
			$scope.modifyAnswer = true;
		}

		$scope.beginmodifyTag = function(){
			$scope.modifyTag = true;
		}

		$scope.follow = function(id){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.question.follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.question.follow = 'Y';
				}
				$scope.question.follow_num = data.follow_num;
			});
		}

		var updateAnswer = function(){
			$scope.pathRecord.currentPath = $location.path();
			var renew = false;
			if ($scope.pathRecord.currentPath != $scope.pathRecord.lastPath)
			{
				$scope.pathRecord.lastPath = $scope.pathRecord.currentPath;
				renew = true;
			}
			aListFactory.refreshAnswer($scope.qid,renew).then(function(){
				var oldLength = $scope.answers.length;
				$scope.answers = aListFactory.getAnswers();
				var newLength = $scope.answers.length;
				if ((newLength - oldLength)<limit){
					$scope.moreanswer = false;
				}
				else{
					$scope.moreanswer = true;
				}
			})
		}

		updateAnswer();

		$scope.update = updateAnswer;

		$scope.like = function(aid,$index){

			$http.get('../CI/index.php/qa_center/good/'+$scope.qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.answers[$index].like = false;
					$scope.answers[$index].cancellike = true;
					$scope.answers[$index].dislike = false;
					$scope.answers[$index].canceldislike = false;
					$scope.answers[$index].good = data.good;
					$scope.answers[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.answers[$index].like = true;
					$scope.answers[$index].cancellike = false;
					$scope.answers[$index].dislike = true;
					$scope.answers[$index].canceldislike = false;
					$scope.answers[$index].good = data.good;
					$scope.answers[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.answers[$index].like = false;
					$scope.answers[$index].cancellike = false;
					$scope.answers[$index].dislike = false;
					$scope.answers[$index].canceldislike = true;
					$scope.answers[$index].good = data.good;
					$scope.answers[$index].bad = data.bad;
				}
			});
		}

		$scope.dislike = function(aid,$index){
			$http.get('../CI/index.php/qa_center/bad/'+$scope.qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.answers[$index].like = false;
					$scope.answers[$index].cancellike = true;
					$scope.answers[$index].dislike = false;
					$scope.answers[$index].canceldislike = false;
					$scope.answers[$index].good = data.good;
					$scope.answers[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.answers[$index].like = true;
					$scope.answers[$index].cancellike = false;
					$scope.answers[$index].dislike = true;
					$scope.answers[$index].canceldislike = false;
					$scope.answers[$index].good = data.good;
					$scope.answers[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.answers[$index].like = false;
					$scope.answers[$index].cancellike = false;
					$scope.answers[$index].dislike = false;
					$scope.answers[$index].canceldislike = true;
					$scope.answers[$index].good = data.good;
					$scope.answers[$index].bad = data.bad;
				}
			});
		}


		$scope.modifyquestion = function(answer,id,qid,$index){
			console.log("hahaha");
			$scope.alert.contentLength = false;
			var postData = {};
			postData.content = answer;
			postData.aid = id;
			var url = '../CI/index.php/qa_center/question_answer/'+qid+'/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: postData,
			}).success(function(response){
                if(response.state == "success")
                {
                	$scope.answers[$index].content = answer;
                	$scope.modifyAnswer = false;
                }
                else{
                	$scope.alert.contentLength = true;
                }
            }).error(function(response){
                alert("Error!");
            });
        };

        $scope.onmodifyTag = function($item, $model, $label){
        	console.log(typeof $scope.question.tag);
			if ($item.tagid == 0){
				var temp = {};
				temp.tagname = $item.tagValue;
				temp.tagid = $item.tagid;
				temp.tagabbr = pinyin.getFullChars(temp.tagname);
				$scope.question.tag.push(temp);
			}
			else{
				$item.tagabbr = pinyin.getFullChars($item.tagname);
				$scope.question.tag.push($item);
			}
			$scope.tag.keyword = "";
		}

		$scope.getModifyTags = function($viewValue){
			var val = {};
			var url = "../CI/index.php/tag_system/tag_search/format/json";
			val.keyword = $viewValue;
			return $http({
				method:'POST',
				url:url,
				data:val,
			}).then(function(res){
				var Results = res.data;
				if(Results.length == 0){
					var temp = {};
					temp.tagname = "添加标签 " + $viewValue;
					temp.tagValue = $viewValue;
					temp.tagid = 0; 
					Results.push(temp);
				}

				return Results;
			})
		}

		$scope.modifyTagSubmit = function(){
			var modifyTagPost = {};
			modifyTagPost.qid = $scope.question.id;
			modifyTagPost.tag = $scope.question.tag;
			console.log(modifyTagPost);
			var url = "../CI/index.php/tag_system/tag_modify/format/json";
			if(modifyTagPost.tag.length == 0 || modifyTagPost.tag.length>5){
				$scope.alert.tagNum = true;
			}
			else{
				$http({
					method:'POST',
					url:url,
					data:modifyTagPost,
				}).then(function(response){
					if(response.data.state == "success"){
						$scope.modifyTag = false;
					}
				})
			}
		}

	}]);

mainControllers.controller('answerCtrl',['$scope','$http',
	function($scope,$http){

		$scope.alert = {};
		$scope.alert.contentLength = false;

		$scope.answerquestion = function(a,q){
			$scope.alert.contentLength = false;
			var url = '../CI/index.php/qa_center/question_answer/'+q+'/format/json/';
			$http({
				method: 'POST',
				url: url,
				data: a,
			}).success(function(response){
                if(response.state == "success")
                {
                	window.location.reload();
                }
                else{
                	$scope.alert.contentLength = true;
                }
            }).error(function(response){
                alert("Error!");
            });
		}
	}]);

mainControllers.controller('oqCtrl',['$scope','$http','qListFactory',
	function($scope,$http,qListFactory){
		$scope.qListA = {};
		$scope.qListT = {};
		$scope.moreA = false;
		$scope.moreT = false;
		var limit = 10;

		var updateListA = function(){
			qListFactory.getQuestiona().then(function(){
				var oldLength = $scope.qListA.length;
				$scope.qListA = qListFactory.getListA();
				var newLength = $scope.qListA.length;
				if ((newLength - oldLength) < limit || newLength<limit){
					$scope.moreA = false;
				}
				else{
					$scope.moreA = true;
				}
			});
		}

		var updateListT = function(){
			qListFactory.getQuestiont().then(function(){
				var oldLength = $scope.qListT.length;
				$scope.qListT = qListFactory.getListT();
				var newLength = $scope.qListT.length;
				if ((newLength - oldLength) < limit || newLength<limit){
					$scope.moreT = false;
				}
				else{
					$scope.moreT = true;
				}
			});
		}

		updateListA();
		updateListT();

		$scope.updateA = updateListA;
		$scope.updateT = updateListT;

		$scope.followA = function(id,$index){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.qListA[$index].follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.qListA[$index].follow = 'Y';
				}
				$scope.qListA[$index].follow_num = data.follow_num;
			});
		}

		$scope.followT = function(id,$index){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.qListT[$index].follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.qListT[$index].follow = 'Y';
				}
				$scope.qListT[$index].follow_num = data.follow_num;
			});
		}

	}]);

mainControllers.controller('notificationCtrl',['$scope','$http','notifyFactory',
	function($scope,$http,notifyFactory){
		$scope.history = {};
		var typemap = {1:'myqAnswered', 2:'myfAnswered', 3:'mygood'};
		for (var i in typemap){
			$scope.history[typemap[i]] = [];
		}
		$scope.more = {};
		$scope.more['1']=false;
		$scope.more['2']=false;
		$scope.more['3']=false;
		var limit = 10;

		var updateHistory = function(type){
			notifyFactory.getHistory(type).then(function(){
				var oldLength = $scope.history[typemap[type]].length;
				$scope.history=notifyFactory.notifyHis();
				var newLength = $scope.history[typemap[type]].length;
				if((newLength - oldLength)<limit || newLength<limit){
					$scope.more[type]=false;
				}
				else{
					$scope.more[type]=true;
				}
			});
		}

		for (var i=1; i<=3; i++){
			updateHistory(i);
		}

		$scope.updateHistory = updateHistory;

		$scope.setRead = function(type){
			console.log($scope.history[typemap[type]]);
			notifyFactory.setRead(type);
			for (var i=0 ; i<$scope.history[typemap[type]].length; i++){
				$scope.history[typemap[type]][i].read = 1;
			}
		}



	}]);

mainControllers.controller('tagCtrl', ['$scope', '$http','$routeParams','tagPageFactory',
	function($scope,$http,$routeParams,tagPageFactory){
		$scope.tagid = $routeParams.tagid;
		$scope.questionList = {};
		$scope.answerList = {};
		$scope.moreA = false;
		$scope.moreQ = false;
		var limit = 10;

		var updateQlist = function(){
			tagPageFactory.getQuestion($scope.tagid).then(function(){
				var oldLength = $scope.questionList.length;
				$scope.questionList = tagPageFactory.getQlist();
				var newLength = $scope.questionList.length;
				if ((newLength - oldLength)<limit || newLength<limit){
					$scope.moreQ = false;
				}
				else{
					$scope.moreQ = true;
				}
			});
		}

		var updateAlist = function(){
			tagPageFactory.getAnswer($scope.tagid).then(function(){
				var oldLength = $scope.answerList.length;
				$scope.answerList = tagPageFactory.getAlist();
				var newLength = $scope.answerList.length;
				if ((newLength - oldLength)<limit || newLength<limit){
					$scope.moreA = false;
				}
				else{
					$scope.moreA = true;
				}
			});
		}

		$scope.updateQlist = updateQlist;
		$scope.updateAlist = updateAlist;

		$http.get("../CI/index.php/tag_system/tag_info/"+$scope.tagid+"/format/json")
		.success(function(data){
			$scope.tag = data;
			if ($scope.tag.follow == 'Y'){
				$scope.flipflop = false;
			}
			else if ($scope.tag.follow == 'N'){
				$scope.flipflop = true;
			}
		});

		updateQlist();
		updateAlist();

		$scope.tagfollow = function(){
			var tagpost = {};
			tagpost.tag = [];
			tagpost.tag[0] = {};
			tagpost.tag[0].tagname = $scope.tag.tagname;
			tagpost.tag[0].tagid = $scope.tagid;
			console.log(tagpost);
			$http({
				method:'POST',
				url:"../CI/index.php/tag_system/user_set_tag/format/json",
				data:tagpost,
			}).then(function(response){
				if(response.data.state == 'success'){
					console.log(response);
				}
		})
		}

		$scope.follow = function(id,$index){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.questionList[$index].follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.questionList[$index].follow = 'Y';
				}
				$scope.questionList[$index].follow_num = data.follow_num;
			});
		}

		$scope.afollow = function(id,$index){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.answerList[$index].follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.answerList[$index].follow = 'Y';
				}
			});
		}

		$scope.alike = function(qid,aid,$index){

			$http.get('../CI/index.php/qa_center/good/'+qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.answerList[$index].like = false;
					$scope.answerList[$index].cancellike = true;
					$scope.answerList[$index].dislike = false;
					$scope.answerList[$index].canceldislike = false;
					$scope.answerList[$index].good = data.good;
					$scope.answerList[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.answerList[$index].like = true;
					$scope.answerList[$index].cancellike = false;
					$scope.answerList[$index].dislike = true;
					$scope.answerList[$index].canceldislike = false;
					$scope.answerList[$index].good = data.good;
					$scope.answerList[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.answerList[$index].like = false;
					$scope.answerList[$index].cancellike = false;
					$scope.answerList[$index].dislike = false;
					$scope.answerList[$index].canceldislike = true;
					$scope.answerList[$index].good = data.good;
					$scope.answerList[$index].bad = data.bad;
				}
			});
		}

		$scope.adislike = function(qid,aid,$index){
			$http.get('../CI/index.php/qa_center/bad/'+qid+'/'+aid+'/format/json').success(function(data){
				if(data.mygood == 1){
					$scope.answerList[$index].like = false;
					$scope.answerList[$index].cancellike = true;
					$scope.answerList[$index].dislike = false;
					$scope.answerList[$index].canceldislike = false;
					$scope.answerList[$index].good = data.good;
					$scope.answerList[$index].bad = data.bad;
				}
				else if(data.mygood == 0){
					$scope.answerList[$index].like = true;
					$scope.answerList[$index].cancellike = false;
					$scope.answerList[$index].dislike = true;
					$scope.answerList[$index].canceldislike = false;
					$scope.answerList[$index].good = data.good;
					$scope.answerList[$index].bad = data.bad;
				}
				else if(data.mygood == -1){
					$scope.answerList[$index].like = false;
					$scope.answerList[$index].cancellike = false;
					$scope.answerList[$index].dislike = false;
					$scope.answerList[$index].canceldislike = true;
					$scope.answerList[$index].good = data.good;
					$scope.answerList[$index].bad = data.bad;
				}
			});
		}


}]);

mainControllers.controller('FileController', ['$scope', 'FileUploader', function($scope, FileUploader) {
        var uploader = $scope.uploader = new FileUploader({
            url: '../CI/index.php/personal_center/modify_profile/format/json'
        });

        // FILTERS

        uploader.filters.push({
            name: 'customFilter',
            fn: function(item /*{File|FileLikeObject}*/, options) {
                return this.queue.length < 1;
            }
        });

        // CALLBACKS

        uploader.onWhenAddingFileFailed = function(item /*{File|FileLikeObject}*/, filter, options) {
            //console.info('onWhenAddingFileFailed', item, filter, options);
        };
        uploader.onAfterAddingFile = function(fileItem) {
            //console.info('onAfterAddingFile', fileItem);
        };
        uploader.onAfterAddingAll = function(addedFileItems) {
            //console.info('onAfterAddingAll', addedFileItems);
        };
        uploader.onBeforeUploadItem = function(item) {
            //console.info('onBeforeUploadItem', item);
        };
        uploader.onProgressItem = function(fileItem, progress) {
            //console.info('onProgressItem', fileItem, progress);
        };
        uploader.onProgressAll = function(progress) {
            //console.info('onProgressAll', progress);
        };
        uploader.onSuccessItem = function(fileItem, response, status, headers) {
        	//window.location.reload();
            //console.info('onSuccessItem', fileItem, response, status, headers);
        };
        uploader.onErrorItem = function(fileItem, response, status, headers) {
            //console.info('onErrorItem', fileItem, response, status, headers);
        };
        uploader.onCancelItem = function(fileItem, response, status, headers) {
            //console.info('onCancelItem', fileItem, response, status, headers);
        };
        uploader.onCompleteItem = function(fileItem, response, status, headers) {

            //console.info('onCompleteItem', fileItem, response, status, headers);
        };
        uploader.onCompleteAll = function() {
        	var t=setTimeout("window.location.reload()",800)
            //console.info('onCompleteAll');
        };
    }]);

