var app = angular.module('surveyApp', []);

app.controller('SurveyController', ['$scope' , '$http', function($scope, $http) {

	$scope.alert = {
		class: '',
		message: ''
	};


	var query = window.location.search.substring(1);
	if(query.length > 1) {
		var splitted = query.split("=");

		$scope.alert.class = 'alert alert-info';
		$scope.alert.message = 'Continuing Survey...' + splitted[1];
		$scope.survey_id = splitted[1];

		$scope.alert.message = "Finidng showroom ";

		$http.get('survey/survey_showroom_id/' + splitted[1]).success(function(data){
			if( !isNaN(data) && data > 0) {

				//$('#showroom_select').prop('selectedIndex' , 3);
				$('#showroom_select').val(data);
				$scope.start_survey();
				
			} else {
				$scope.alert.class = 'alert alert-danger';
				$scope.alert.message = "Wrong Survey ID or showroom doesn't exist with this survey id ";
			}
		});

	} else {
		console.log("nai");
	}
    





	$scope.start_survey = function() {

		$scope.showroom_id = $('#showroom_select').val();

		if($scope.survey_id == undefined) {
			$scope.survey_id = '';
		}



		$scope.alert.class = 'alert alert-info';
		$scope.alert.message = 'Listing items...';
		var responsePromise = $http.get('survey/get_items/'+$scope.showroom_id + '/' + $scope.survey_id).success(function(data){
			$scope.survey_id = data.survey_id;

			console.log($scope.survey_id);

			$scope.items = data.items;
			$scope.stock_count = data.length;
			$scope.alert.class = 'alert alert-success';
			$scope.alert.message = 'Listing done';

			$scope.items_found = 0;
			if(data.total_items != undefined) {
				$scope.total_items = data.total_items;
				$scope.items_found = data.total_items - data.items.length;
			} else {
				$scope.total_items = data.items.length;	
			}
			
		});

		$('#barcode_field').keyup(function(e){
		
			if(e.keyCode == 13) {
				if($scope.barcode == '') return;


				console.log($scope.barcode);
				var id = parseInt($scope.barcode);
				//console.log(id == NaN);
				$.ajax({
						method: "POST",
						data: {'item_id' : id, 'survey_id' : $scope.survey_id},
					    url: "survey/accept_item",
					    error: function(){
					    	$scope.barcode = '';
					        alert("error while submitting data, try again");
					        $scope.alert.class = 'alert alert-success';
							$scope.alert.message = "error while submitting data, try again";
					    },
					    success: function(response){
					    	console.log(response);
							if(response == 'ok') {

								$scope.$apply(function(){
									var removed = false;
									_.remove($scope.items, function(item){
										if(item.id == id) {
											removed = true;
											$scope.items_found = $scope.items_found + 1;
											//console.log($scope.items_found);
											$scope.barcode = '';
											return true;
										} else {
											return false;
										}
									});


									if(removed == false) {
										$scope.alert.class = 'alert alert-danger';
										$scope.alert.message = id + " not found";
									} else {
										$scope.alert.class = 'alert alert-success';
										$scope.alert.message = id + " found!";
									}


								});
							}        
					    },
					    timeout: 10000 // sets timeout to 3 seconds
					});
			}
		});
	}

	$scope.submit_survey = function() {

		var not_found_ids = [];
		angular.forEach($scope.items, function(item){
			not_found_ids.push(item.id);
		});

		var survey_data = {
			survey_id: $scope.survey_id,
		};

		$scope.alert.class = "alert alert-info";
		$scope.alert.message = "submitting data to server...";

		$.post('survey/new_survey' ,{'val' : JSON.stringify(survey_data)} , function(response){
			console.log(response);

			$scope.$apply(function(){
				$scope.alert.class = "alert alert-success";
				$scope.alert.message = 'Report has been submitted successfully';	
				$('#barcode_field').val('');
				$scope.total_items = undefined;
				$scope.items = [];
			});
		});
	}

}]);

