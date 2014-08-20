'use strict';

/* Services */
mainApp.factory('publicFactory', function($http,$q){
	var publicFn = {};
	
	var _myid = '';
	var _myrealname = '';
	var _mysmallPic = '';
	var _mymiddlePic = '';
	var _mylargePic = '';

	publicFn.initMe = function(){
		var deferred = $q.defer();
		$http.get("../CI/index.php/public_function/myinfo/format/json")
		.success(function(data){
			_myid = data.myuid;
			_myrealname = data.myrealname;
			_mysmallPic = data.location_small;
			_mymiddlePic = data.location_middle;
			_mylargePic = data.location_large;
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}


	publicFn.isReady = function(){
		return _initReady;
	}

	publicFn.getSelfid = function(){
		return _myid;
	}

	publicFn.getSelfname = function(){
		return _myrealname;
	}

	publicFn.getSelfPicS = function(){
		return _mysmallPic;
	}

	publicFn.getSelfPicM = function(){
		return _mymiddlePic;
	}

	publicFn.getSelfPicL = function(){
		return _mylargePic;
	}

	return publicFn;
});


mainApp.factory('qListFactory', function($http, $q){
	var questionFn = {};

	var _limit = 10;
	var _qListT = [];//tag
	var _qListA = [];//all

	questionFn.getQuestiont = function(){
		var offset = 0;
		for(var q in _qListT){
			offset++;
		}
		var deferred = $q.defer();
		$http.get("../CI/index.php/home/question_tag_list/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(data == "")
			{
				console.log("no more questions");
			}
			else{
				_qListT = _qListT.concat(data);
			}
			
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	questionFn.getQuestiona = function(){
		var offset = 0;
		for(var q in _qListA){
			offset++;
		}
		var deferred = $q.defer();
		$http.get("../CI/index.php/home/question_date_list/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(data == "")
			{
				console.log("no more questions");
			}
			else{
				_qListA = _qListA.concat(data);
			}
			
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	questionFn.getListA = function(){
		return _qListA;
	}

	questionFn.getListT = function(){
		return _qListT;
	}

	return questionFn;
});

mainApp.factory('homeFeedFactory', function($http, $q){
	var homeFeedFn = {};

	var _limit = 10;
	var _tagTAL = [];
	var _allTAL = [];

	homeFeedFn.getTagTAL = function(){
		var offset = 0;
		for(var q in _tagTAL){
			offset ++;
		}
		var deferred = $q.defer();
		$http.get("../CI/index.php/home/user_tag_list/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(data == ""){
				console.log("H/UTL void");
			}
			else{
				_tagTAL = _tagTAL.concat(data);
			}

			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	homeFeedFn.getAllTAL = function(){
		var offset = 0;
		for(var q in _allTAL){
			offset ++;
		}
		var deferred = $q.defer();
		$http.get("../CI/index.php/home/question_hurry_list/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(data == ""){
				console.log("H/UTL void");
			}
			else{
				_allTAL = _allTAL.concat(data);
			}

			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	homeFeedFn.tagTopAnswerList = function(){
		for (var i=0; i<_tagTAL.length; i++){
			if (_tagTAL[i].mygood == 1){
				_tagTAL[i].like = false;
				_tagTAL[i].cancellike = true;
				_tagTAL[i].dislike = false;
				_tagTAL[i].canceldislike = false;
			}
			else if (_tagTAL[i].mygood == 0)
			{
				_tagTAL[i].like = true;
				_tagTAL[i].cancellike = false;
				_tagTAL[i].dislike = true;
				_tagTAL[i].canceldislike = false;
			}
			else if (_tagTAL[i].mygood == -1)
			{
				_tagTAL[i].like = false;
				_tagTAL[i].cancellike = false;
				_tagTAL[i].dislike = false;
				_tagTAL[i].cancellike = true;
			}
		}
		return _tagTAL;
	}

	homeFeedFn.allTopAnswerList = function(){
		for (var i=0; i<_allTAL.length; i++){
			if (_allTAL[i].mygood == 1){
				_allTAL[i].like = false;
				_allTAL[i].cancellike = true;
				_allTAL[i].dislike = false;
				_allTAL[i].canceldislike = false;
			}
			else if (_allTAL[i].mygood == 0)
			{
				_allTAL[i].like = true;
				_allTAL[i].cancellike = false;
				_allTAL[i].dislike = true;
				_allTAL[i].canceldislike = false;
			}
			else if (_allTAL[i].mygood == -1)
			{
				_allTAL[i].like = false;
				_allTAL[i].cancellike = false;
				_allTAL[i].dislike = false;
				_allTAL[i].cancellike = true;
			}
		}
		return _allTAL;
	}

	return homeFeedFn;
});

mainApp.factory('aListFactory', function($http,$q){
	var aListFn = {};

	var _question = {};
	var _aList = [];

	var _aLimit = 20;

	aListFn.refreshQuestion = function(qid){
		var deferred = $q.defer();
		$http.get('../CI/index.php/qa_center/view_question/'+ qid +'/format/json')
		.success(function(data){
			_question = data;
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	aListFn.refreshAnswer = function(qid,renew){
		if (renew){
			_aList.length=0;
		}
		var offset = 0;
		for (var a in _aList){
			offset++;
		}
		var deferred = $q.defer();
		$http.get('../CI/index.php/qa_center/view_answer/'+qid+'/0/'+_aLimit+'/'+offset+'/format/json')
		.success(function(data){
			if(typeof data == typeof "string"){
				console.log("no more answers");
			}
			else{
				_aList = _aList.concat(data);
			}

			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	aListFn.getQuestion = function(){
		return _question;
	}

	aListFn.getAnswers = function(){
		for (var i=0; i<_aList.length; i++){
			if (_aList[i].mygood == 1){
				_aList[i].like = false;
				_aList[i].cancellike = true;
				_aList[i].dislike = false;
				_aList[i].canceldislike = false;
			}
			else if (_aList[i].mygood == 0)
			{
				_aList[i].like = true;
				_aList[i].cancellike = false;
				_aList[i].dislike = true;
				_aList[i].canceldislike = false;
			}
			else if (_aList[i].mygood == -1)
			{
				_aList[i].like = false;
				_aList[i].cancellike = false;
				_aList[i].dislike = false;
				_aList[i].cancellike = true;
			}
		}
		return _aList;
	}

	return aListFn;
});

mainApp.factory('peopleFactory', function($http,$q){
	var peopleFn = {};

	var _isMe = false;
	var _realname = "";
	var _gender = "";
	var _occupation = "";
	var _job = "";
	var _jobtime = "";
	var _province = "";
	var _jobplace = "";
	var _bio = "";
	var _location = "";

	var _qList = [];	// question
	var _aList = [];	// answer
	var _fList = [];	// follow

	var _limit = 3;

	peopleFn.profileInit = function(id,renew){
		var deferred = $q.defer();
		if(renew){
			_qList.length = 0;
			_aList.length = 0;
			_fList.length = 0;
		}
		$http.get("../CI/index.php/personal_center/get_profile/"+id+"/format/json")
		.success(function(data){
			if (data.myprofile == 1){
				_isMe = true;
			}
			else if (data.myprofile == 0){
				_isMe = false;
			}
			_realname = data.realname;
			_gender = data.gender;
			_occupation = data.occupation;
			_job = data.job;
			_jobtime = data.jobtime;
			_province = data.province;
			_jobplace = data.jobplace;
			_bio = data.bio;
			_location = data.location;
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	peopleFn.refreshQList = function(id){
		var offset = 0;
		for(var q in _qList){
			offset++;
		}
		var deferred = $q.defer();
		$http.get("../CI/index.php/personal_center/my_question/"+id+"/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(typeof data == typeof "string")
			{
				console.log("no more my_questions");
			}
			else{
				_qList = _qList.concat(data);
			}
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	peopleFn.refreshAList = function(id){
		var offset = 0;
		for(var a in _aList){
			offset++;
		}
		var deferred = $q.defer();

		$http.get("../CI/index.php/personal_center/my_answer/"+id+"/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(typeof data == typeof "string")
			{
				console.log("no more my_answers");
			}
			else{
				_aList = _aList.concat(data);
			}
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	peopleFn.refreshFList = function(id){
		var offset = 0;
		for(var f in _fList){
			offset++;
		}
		var deferred = $q.defer();

		$http.get("../CI/index.php/personal_center/my_follow_question/"+id+"/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(typeof data == typeof "string")
			{
				console.log("no more my_follow_questions");
			}
			else{
				_fList = _fList.concat(data);
			}
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	peopleFn.getQlist = function(){
		return _qList;
	}

	peopleFn.getAlist = function(){
		return _aList;
	}

	peopleFn.getFlist = function(){
		return _fList;
	}

	peopleFn.isMe = function(){
		return _isMe;
	}

	peopleFn.getRealname = function(){
		return _realname;
	}

	peopleFn.getGender = function(){
		if (_gender == 'M'){
			return true;
		}
		else if (_gender == 'F'){
			return false;
		}
		else{
			console.log("error!");
			return false;
		}
	}

	peopleFn.isStudent = function(){
		if (_occupation == 'S'){
			return true;
		}
		else if (_occupation == 'W'){
			return false;
		}
		else{
			console.log("error!");
			return false;
		}
	}

	peopleFn.getPosition = function(){
		return _job;
	}

	peopleFn.getMajor = function(){
		return _job;
	}

	peopleFn.getYear = function(){
		return _jobtime;
	}

	peopleFn.getProvince = function(){
		return _province;
	}

	peopleFn.getCompany = function(){
		return _jobplace;
	}

	peopleFn.getCollege = function(){
		return _jobplace;
	}

	peopleFn.getBio = function(){
		return _bio;
	}

	peopleFn.getPic = function(){
		return _location;
	}

	return peopleFn;
});

mainApp.factory('notifyFactory', function($http,$q){
	var notification = {};

	notification.checkNew = function(){
		var deferred = $q.defer();
		$http.get("../CI/index.php/notify/new_notification/format/json")
		.success(function(response){
			deferred.resolve(response);
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	

	return notification;
});

mainApp.factory('msgFactory', function($http, $q){
	var letterFn = {};

	letterFn.checkNew = function(){
		var deferred = $q.defer();
		$http.get("../CI/index.php/letter/letter_notify/format/json")
		.success(function(response){
			deferred.resolve(response);
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	letterFn.setRead = function(){
		var deferred = $q.defer();
		$http.get("../CI/index.php/letter/letter_set_look/format/json")
		.success(function(response){
			if(response.state != 'success'){
				console.log("Error");
			}
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	letterFn.messageList = function(){
		var deferred = $q.defer();
		$http.get("../CI/index.php/letter/letter_home/100/0/format/json")
		.success(function(response){
			deferred.resolve(response);
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	return letterFn;
});

mainApp.factory('tagPageFactory', function($http, $q){
	var tagFn = {};

	var _limit = 10;
	var _Qlist = [];
	var _Alist = [];

	tagFn.getQuestion = function(tagid){
		var offset = 0;
		for(var q in _Qlist){
			offset++;
		}
		var deferred = $q.defer();
		$http.get("../CI/index.php/tag_system/tag_question_list/"+tagid+"/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(typeof data == "string")
			{
				console.log("no more questions");
			}
			else{
				_Qlist = _Qlist.concat(data);
			}
			
			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	tagFn.getQlist = function(){
		return _Qlist;
	}

	tagFn.getAnswer = function(tagid){
		var offset = 0;
		for(var q in _Alist){
			offset ++;
		}
		var deferred = $q.defer();
		$http.get("../CI/index.php/tag_system/tag_hot_question_list/"+tagid+"/"+_limit+"/"+offset+"/format/json")
		.success(function(data){
			if(data == ""){
				console.log("no more answers");
			}
			else{
				_Alist = _Alist.concat(data);
			}

			deferred.resolve();
		}).error(function(){
			deferred.reject("Error");
		});

		return deferred.promise;
	}

	tagFn.getAlist = function(){
		for (var i=0; i<_Alist.length; i++){
			if (_Alist[i].mygood == 1){
				_Alist[i].like = false;
				_Alist[i].cancellike = true;
				_Alist[i].dislike = false;
				_Alist[i].canceldislike = false;
			}
			else if (_Alist[i].mygood == 0)
			{
				_Alist[i].like = true;
				_Alist[i].cancellike = false;
				_Alist[i].dislike = true;
				_Alist[i].canceldislike = false;
			}
			else if (_Alist[i].mygood == -1)
			{
				_Alist[i].like = false;
				_Alist[i].cancellike = false;
				_Alist[i].dislike = false;
				_Alist[i].cancellike = true;
			}
		}
		return _Alist;
	}

	return tagFn;
});