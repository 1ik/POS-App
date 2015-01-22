
<style type="text/css">
    .item_table p{
        font-weight: 600;
        font-size: 20px;
    }
    
    .remove{
        cursor: pointer;
    }
    th {
    	text-align: center;
    }

    .invisible{
        display : none;
    }

    .tabbbleContainer{
        width : 100%;
        height : 350px;
        overflow-y:scroll;
        display: block;
    }

    .visible{
        display : block;
        display : inline;
    }
    
    .loader_visible{
        background-image:url('<?php echo base_url(); ?>assets/img/transparent-ajax-loader.gif');
        background-position: 95%;
        background-repeat: no-repeat;
    }
    .stock-information {

    }

</style>



<div class="container" ng-app="surveyApp">
	<div class="row" ng-controller="SurveyController" style="margin-left : 30px">

		<div class="row"> 
			<div class="col-xs-4">
				<div class="form-group">
					<label for="showroom_select"> Choose showroom</label>
			    	<select class="form-control" id="showroom_select">
			    		<?php foreach ($showrooms as $showroom) { ?>
			    			<option value="<?php echo $showroom->id?>"><?php echo $showroom->name; ?></option>
			    		<?php } ?>
			    	</select>
			  	</div>
			</div>

			<div class="col-xs-7">
				<button style="margin-top : 20px;" class="btn btn-default" ng-click="start_survey()">Start Survey</button>
				<button ng-show="total_items != undefined" style="margin-top : 20px;" class="btn btn-success pull-right" ng-click="submit_survey()">Survey finished!</button>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
				<div class="form-group" >
					<input type="text" id="barcode_field" ng-model="barcode" class="form-control" placeholder="barcode"  />
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-4">
				<div ng-class="alert.class" role="alert" ng-show="alert.message != ''">
					{{alert.message}}
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
				</div>
			</div>

			<div class="col-xs-7 stock-information" style="font-size : 15px; margin : 0px 0px 10px 10px" ng-show="total_items != undefined">
				<span class="alert alert-warning pull-right">Items left : {{total_items - items_found}}</span>
				<span class="alert alert-success pull-right">Items found : {{items_found}}</span>
				<span class="alert alert-info pull-right">Total Items : {{total_items}}</span>
			</div>
		</div>



		<div class="row">
		    <div class="col-md-11 item_table">
		        <div class="tabbbleContainer">
		            <table class="table table-bordered" id="transfer_table">
		            	<thead>
			                <th>Item id</th>
							<th>Color</th>
							<th>Size</th>
							<th>Item type</th>
							<th>Price</th>
		                </thead>

		                <tbody>
							<tr ng-repeat="item in items">
								<td>{{item.id}}</td>
								<td>{{item.color_code}}</td>
								<td>{{item.size}}</td>
								<td>{{item.type}}</td>
								<td>{{item.sell_price}}</td>
							</tr>
						</tbody>
		            </table>
		            <a id="bottomOfDiv"></a>
		        </div>
		    </div>
		</div>


	</div> <!-- surveycontroller-->
</div>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/lodash.js/2.4.1/lodash.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.22/angular.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/survey_script.js"></script>