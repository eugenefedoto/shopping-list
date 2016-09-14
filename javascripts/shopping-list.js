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

		$scope.types = [];
		$scope.items = [];

		$scope.item = '';
		$scope.qty = '';
		$scope.types = '';

		$scope.howManyMoreCharactersNeeded = function () {
			var characters = (MIN_LENGTH - $scope.item.length);

			return (characters > 0) ? characters : 0;
		};

		$scope.howManyCharactersRemaining = function() {
			characters = (MAX_LENGTH - $scope.item.length);

			return (characters > 0) ? characters : 0;
		};

		$scope.howManyCharactersOver = function() {
			var characters = (MAX_LENGTH - $scope.item.length);

			return (characters < 0) ? Math.abs(characters) : 0;
		};

		$scope.minimumCharactersMet = function() {
			return ($scope.howManyMoreCharactersNeeded() == 0);
		};

		$scope.anyCharactersOver = function() {
			return ($scope.howManyCharactersOver > 0);
		};

		$scope.isNumberOfCharactersWithinRange = function() {
			return (
				$scope.minimumCharactersMet() &&
				!$scope.anyCharactersOver()
			);
		};

		$scope.goodToGo = function() {
			return (
				$scope.isNumberOfCharactersWithinRange() &&
				$scope.qty > 0 &&
				$scope.types > 0
			);
		};
	} );