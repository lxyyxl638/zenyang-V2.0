'use strict';

// var signApp = angular.module('signApp',[
// 	'ngRoute',
// 	'signControllers'
// 	]);

var mainApp = angular.module('mainApp', ['ngRoute','angularFileUpload','ui.bootstrap','textAngular',
	'mainControllers'], function($httpProvider) {
  // Use x-www-form-urlencoded Content-Type
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
 
  /**
   * The workhorse; converts an object to x-www-form-urlencoded serialization.
   * @param {Object} obj
   * @return {String}
   */ 
  var param = function(obj) {
    var query = '', name, value, fullSubName, subName, subValue, innerObj, i;
      
    for(name in obj) {
      value = obj[name];
        
      if(value instanceof Array) {
        for(i=0; i<value.length; ++i) {
          subValue = value[i];
          fullSubName = name + '[' + i + ']';
          innerObj = {};
          innerObj[fullSubName] = subValue;
          query += param(innerObj) + '&';
        }
      }
      else if(value instanceof Object) {
        for(subName in value) {
          subValue = value[subName];
          fullSubName = name + '[' + subName + ']';
          innerObj = {};
          innerObj[fullSubName] = subValue;
          query += param(innerObj) + '&';
        }
      }
      else if(value !== undefined && value !== null)
        query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
    }
      
    return query.length ? query.substr(0, query.length - 1) : query;
  };
 
  // Override $http service's default transformRequest
  $httpProvider.defaults.transformRequest = [function(data) {
    return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
  }];
});

mainApp.config(['$provide',
  function($provide) {
    $provide.decorator('taOptions', ['taRegisterTool', '$modal', '$delegate',
      function(taRegisterTool, $modal, taOptions) {
        // $delegate is the taOptions we are decorating
        // here we override the default toolbars specified in taOptions.
        taOptions.toolbar = [
          ['p','quote',
          'bold', 'italics', 'underline', 'ul', 'ol', 'redo', 'undo', 'clear',
          'indent','outdent',
          ]
        ];

        taRegisterTool('customInsertLink',{
          iconclass: "fa fa-link",
          action: function($deferred){
            var textAngular = this;
            var savedSelection = rangy.saveSelection();
            var modalInstance = $modal.open({
              template: '<div class="model-dialog-title"><span class="model-dialog-title-text">插入链接</span><span class="model-dialog-title-close" id="img-dialog-title-close" ng-click="cancel()"></span></div><div class="link-modal"><input type="text" class="form-control input-lg" placeholder="http://..." ng-model="link.url"/><div class="link-btn-wrap"><button class="btn btn-primary btn-link" ng-click="submit()">确定</button></div><div>',
              size:'sm',
              controller:['$modalInstance','$scope',
                function($modalInstance,$scope){
                  $scope.link = {
                    url:''
                  };
                  $scope.submit = function(){
                    console.log($scope.link.url);
                    $modalInstance.close($scope.link.url);
                  }
                  $scope.cancel = function () {
                     $modalInstance.dismiss('cancel');
                   };
                }
              ]
            });

            modalInstance.result.then(function(linkurl){
              rangy.restoreSelection(savedSelection);
              textAngular.$editor().wrapSelection('createlink', linkurl);
              $deferred.resolve();
            });

            return false;
          }
        });

        taRegisterTool('customInsertImage', {
          iconclass: "fa fa-picture-o",
          action: function($deferred) {
            var textAngular = this;
            var savedSelection = rangy.saveSelection();
            var modalInstance = $modal.open({
              // Put a link to your template here or whatever
              templateUrl: 'views/addImgModal.html',
              size: 'lg',
              controller: ['$modalInstance', '$scope','FileUploader',
                function($modalInstance, $scope,FileUploader) {
                  var uploader = $scope.uploader = new FileUploader({
                    url: '../CI/index.php/public_function/upload/format/json'
                  });

                  $scope.img = {
                    url: ''
                  };

                  uploader.onSuccessItem = function(fileItem, response, status, headers) {
                    $scope.img.url = response;
                    $scope.img.url = $scope.img.url.replace(/\"/g, "");
                  };

                  $scope.submit = function() {
                    $modalInstance.close($scope.img.url);
                  };

                  $scope.cancel = function () {
                    $modalInstance.dismiss('cancel');
                  };
                }
              ]
            });

            modalInstance.result.then(function(imgUrl) {
              rangy.restoreSelection(savedSelection);
              textAngular.$editor().wrapSelection('insertImage', imgUrl);
              $deferred.resolve();
            });
            return false;
          },
        });

        // Now add the button to the default toolbar definition
        // Note: It'll be the last button
        taOptions.toolbar[0].push('customInsertLink');
        taOptions.toolbar[0].push('customInsertImage');
        return taOptions;
      }
    ]);
  }
]);

mainApp.config(['$routeProvider',
	function($routeProvider){
		$routeProvider.
		when('/',{
			templateUrl:'views/main.html'
		}).
    when('/open-question',{
      templateUrl:'views/openquestion.html'
    }).
    when('/notification',{
      templateUrl:'views/notification.html'
    }).
    when('/people/:uid',{
      templateUrl:'views/people.html'
    }).
    when('/message',{
      templateUrl:'views/message.html'
    }).
    when('/setting',{
      templateUrl:'views/settings.html'
    }).
    when('/question/:id',{
      templateUrl:'views/question.html'
    }).
    when('/talk/:uid',{
      templateUrl:'views/talk.html'
    }).
    when('/search',{
      templateUrl:'views/search.html'
    }).
    when('/tag/:tagid',{
      templateUrl:'views/tag.html'
    }).
		otherwise({
			redirectTo: '/'
		});

	}]);