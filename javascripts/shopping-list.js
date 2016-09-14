"use strict";

angular.module('myApp', [])
	.constant('MAX_LENGTH', 50)
	.constant('MIN_LENGTH', 2)

	.factory('helperFactory', function() {
		return {
			filterFieldArrayByDone : function(thisArray, thisField, thisValue) {
				var arrayToReturn = [];

				for (var i = 0; i < thisArray.length; i++) {
					if (thisArray[i].done == thisValue) {
						arrayToReturn.push(thisArray[i][thisField]);
					} 
				}

				return arrayToReturn;
			}
		};
	})

	.controller('ShoppingListController', function($scope, $http, $log, $help, helperFactory, 
		MAX_LENGTH, MIN_LENGTH) {
		var urlInsert = '/mod/insert.php';
		var urlSelect = '/mod/select.php';
		var urlUpdate = '/mod/update.php';
		var urlRemove = '/mod/remove.php';
	} )