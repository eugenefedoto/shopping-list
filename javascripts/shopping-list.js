"use strict";

var app = angular.module('myApp', []);
var MAX_LENGTH = 50;
var MIN_LENGTH = 2;

app.factory('helperFactory', function() {
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
	});

app.controller('ShoppingListController', ['$scope', '$http', '$log', 'helperFactory', function($scope, $http, $log, helperFactory) {
		var urlInsert = '/shopping-list/mod/insert.php';
		var urlSelect = '/shopping-list/mod/select.php';
		var urlUpdate = '/shopping-list/mod/update.php';
		var urlRemove = '/shopping-list/mod/remove.php';

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
			var characters = (MAX_LENGTH - $scope.item.length);

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
			return ($scope.howManyCharactersOver() > 0);
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
				Number($scope.qty) > 0 &&
				$scope.types.length > 0
			);
		};

		function _recordAddedSuccessfully(data) {
			return (
				data.data &&
				!data.data.error &&
				data.data.item
			);
		};

		$scope.clear = function() {
			$scope.item = '';
			$scope.qty = '';
		};

		$scope.insert = function() {

			if ($scope.goodToGo()) {

				var thisData = 'item=' + $scope.item; 
				thisData += '&qty=' + $scope.qty; 
				thisData += '&type=' + $scope.type;
				$http({
					method : 'POST',
					url : urlInsert,
					data : {
						'item' : $scope.item,
						'qty' : $scope.qty,
						'type' : $scope.type
					}
				}).then(function(data) {

							if (_recordAddedSuccessfully(data)) {
								$scope.items.push({

									id : data.data.item.id,
									item : data.data.item.item,
									qty : data.data.item.qty,
									type : data.data.item.type,
									type_name : data.data.item.type_name,
									done : data.data.item.done
							
								});
								
								//$scope.$apply();
								$scope.clear();
							}

					}, function(data, status, headers, config) {
						throw new Error('Something went wrong with inserting record')
					});
			}
		};

		$scope.select = function() {
			$http.get(urlSelect)
				.success(function(data) {
					if (data.items) {
						$scope.items = data.items;
					}

					if (data.types) {
						$scope.types = data.types;
						$scope.type = $scope.types[0].id;
					}
				})
				.error(function(data, status, headers, config) {
					throw new Error('Something went wrong with selecting records')
				});
		};

		$scope.select();


		$scope.update = function(item) {

				$http({
					method : 'POST',
					url : urlUpdate,
					data : {
						'id' : item.id,
						'done' : item.done,
					}
				}).then(function(data) {

							//$log.info(data);

					}, function(data, status, headers, config) {
						throw new Error('Something went wrong with updating record')
					});
		};


		function _recordRemovedSuccessfully(data) {
			return (
				data.data &&
				!data.data.error
			);
		}

		$scope.remove = function() {
			var removeIds = helperFactory.filterFieldArrayByDone($scope.items, 'id', 1);

			if (removeIds.length > 0) {
				$http({
					method: 'POST',
					url: urlRemove,
					data: 'ids=' + removeIds.join('|'),
					headers: {'Content-type' : 'application/x-www-form-urlencoded'}
				})
					.success(function(data) {
						if (_recordAddedSuccessfully(data)) {
							$scope.items = $scope.items.filter(function(item) {
								return item.done == 0;
							});
						}
					})
					.error(function(data, status, headers, config) {
						throw new Error('Something went wrong with selecting records')
					});
			}
		};

		$scope.print = function() {
			window.print();
		};
	}]);