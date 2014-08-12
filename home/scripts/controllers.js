'use strict';

/* Controllers */

var mainControllers = angular.module('mainControllers',[]);

mainControllers.controller('navCtrl',['$scope', '$interval','publicFactory','$http','notifyFactory','$location','$modal','msgFactory',
	function($scope,$interval,publicFactory,$http,notifyFactory,$location,$modal,msgFactory){
		
		// Notification Defines
		$scope.notify = {};
		$scope.notify.show = false;

		$scope.msg = {};
		$scope.msg.show = false;

		var checkNotifyRoutine = function(){
			notifyFactory.checkNew().then(function(response){
				if (typeof response.num !== undefined){
					if(response.num == 0){
						$scope.notify.show = false;
						$scope.notify.totalNum = 0;
						$scope.notify.answerNum = 0;
						$scope.notify.goodNum = 0;
						$scope.notify.followNum = 0;
					}
					else{
						$scope.notify.show = true;
						$scope.notify.totalNum = response.num;
						$scope.notify.answerNum = response.num_1;
						$scope.notify.goodNum = response.num_2;
						$scope.notify.followNum = response.num_3;
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
					}
				}
			})
		};

		// Init user basic infomation
		publicFactory.initMe().then(function(){
			$scope.realname = publicFactory.getSelfname();
			$scope.pic = publicFactory.getSelfPicS();
			$scope.uid = publicFactory.getSelfid();
		});

		// Notification routine
		checkNotifyRoutine();
		var checkNotify = $interval(checkNotifyRoutine,60000,0,false);

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

		$scope.pathRecord = {};
		$scope.pathRecord.currentPath = $location.path();
		$scope.pathRecord.lastPath = $scope.pathRecord.currentPath;

		$scope.peoplePathRecord = {};
		$scope.peoplePathRecord.currentPath = $location.path();
		$scope.peoplePathRecord.lastPath = $location.path();

	}]);

mainControllers.controller('mainCtrl',['$scope','$http',
	function($scope,$http){
		// $scope.qList={};
		// if($.isEmptyObject($scope.qList))
		// {
		// 	$http.get('../CI/index.php/QA_center/question_date/format/json').success(function(data){
		// 		$scope.qList = data;
		// 	});
		// }
		// $scope.questionByDate = function() {
		// 	$http.get('../CI/index.php/QA_center/question_date/format/json').success(function(data){
		// 		$scope.qList = data;
		// 	});
		// }
		// $scope.questionByUser = function() {
		// 	$http.get('../CI/index.php/QA_center/question_focus/format/json').success(function(data){
		// 		if (typeof(data) == typeof("string"))
		// 		{
		// 			$scope.qList = {};
		// 		}
		// 		else
		// 		{
		// 			$scope.qList = data;
		// 		}
		// 	});
		// }
		// $scope.questionByDay = function() {
		// 	$http.get('../CI/index.php/QA_center/question_day/format/json').success(function(data){
		// 		$scope.qList = data;
		// 	});
		// }
		// $scope.follow = function(id,$index){
		// 	$http.get('../CI/index.php/QA_center/question_attention/'+id+'/format/json').success(function(data){
		// 		if(data.follow == 0)
		// 		{
		// 			$scope.qList[$index].follow = 0;
		// 		}
		// 		else if (data.follow == 1)
		// 		{
		// 			$scope.qList[$index].follow = 1;
		// 		}
		// 	});
		// }
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
				$scope.myquestion = peopleFactory.getQlist();
			})
		};

		var updateMyA = function(){
			peopleFactory.refreshAList($scope.uid).then(function(){
				$scope.myanswer = peopleFactory.getAlist();
			})
		}

		var updateMyF = function(){
			peopleFactory.refreshFList($scope.uid).then(function(){
				$scope.myfollow = peopleFactory.getFlist();
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
		// $scope.uid = $routeParams.uid;
		// $http.get('../CI/index.php/public_function/uidrealname/'+ $scope.uid +'/format/json').success(function(data){
		// 		$scope.realname = data.uidrealname;
		// 	});
	}]);

mainControllers.controller('questionCtrl',['$scope','$http','$routeParams','aListFactory','$location',
	function($scope,$http,$routeParams,aListFactory,$location){
		$scope.question = {};
		$scope.answers = [];
		$scope.qid = $routeParams.id;

		aListFactory.refreshQuestion($scope.qid).then(function(){
			$scope.question = aListFactory.getQuestion();
		});

		var updateAnswer = function(){
			$scope.pathRecord.currentPath = $location.path();
			var renew = false;
			if ($scope.pathRecord.currentPath != $scope.pathRecord.lastPath)
			{
				$scope.pathRecord.lastPath = $scope.pathRecord.currentPath;
				renew = true;
			}
			aListFactory.refreshAnswer($scope.qid,renew).then(function(){
				$scope.answers = aListFactory.getAnswers();
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

mainControllers.controller('oqCtrl',['$scope','$http','qListFactory','publicFactory',
	function($scope,$http,qListFactory,publicFactory,peopleFactory){
		$scope.qList = {};
		$scope.myPic = publicFactory.getSelfPicS();

		var updateList = function(){
			qListFactory.getQuestion().then(function(){
				$scope.qList = qListFactory.getList();
				$scope.myPic = publicFactory.getSelfPicS();
			});
		}
		
		updateList();

		$scope.update = updateList;

		$scope.follow = function(id,$index){
			$http.get('../CI/index.php/qa_center/question_follow/'+id+'/format/json').success(function(data){
				if(data.follow == 'N')
				{
					$scope.qList[$index].follow = 'N';
				}
				else if (data.follow == 'Y')
				{
					$scope.qList[$index].follow = 'Y';
				}
				$scope.qList[$index].follow_num = data.follow_num;
			});
		}


	}]);

mainControllers.controller('notificationCtrl',['$scope','$http',
	function($scope,$http){
		
		$http.get("../CI/index.php/notify/follow_new_answer/100/0")
		.success(function(data){
			if(typeof data == typeof ("string")){
				$scope.FQnew = {};
			}
			else{
				$scope.FQnew = data;
			}
		});

		$http.get("../CI/index.php/notify/myquestion_new_answer/100/0")
		.success(function(data){
			if(typeof data == typeof ("string")){
				$scope.MQnew = {};
			}
			else{				
				$scope.MQnew = data;
			}
		});

		$http.get("../CI/index.php/notify/myanswer_get_good/100/0")
		.success(function(data){
			if(typeof data == typeof ("string")){
				$scope.AGnew = {};
			}
			else{
				$scope.AGnew = data;
			}
		});

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
            //console.info('onCompleteAll');
        };

        //console.info('uploader', uploader);
    }]);

