
 <html>
 <head>
 	<title></title>
 	<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">
 	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,600,800,700,300' rel='stylesheet' type='text/css'>

 	<style type="text/css" media="all">
 		.heading{
 			text-align: center;
 			width: 100%;
 			margin : 0 auto;
 		}
    .name {
      margin-top: 40px;
    }

    p.phone {
      margin: 0;
      padding: 0;
    }

 		h1,h2,h3,h4,p,h5,h6{
 			font-family: 'Open Sans', sans-serif;
 			padding : 0;
 		}

    tr td {
      font-size: 10px;
    }
  

    .showprint {
      display: none;
    }

    .showdesktop {
      display: block;
    }

    @media print {
      .showprint {
        display: block;
      }
      .showdesktop {
        display: none;
      }
    }

 		.heading h1{
 			font-size: 45px;
 		}


 		.signature{
 			 			
 		}



 		.debug{
 			border  :2px dotted green;
 		}

 		#transfer_id  {
 			position: absolute;
 			font-size: 20px;
 			font-weight: 600;
 		}


footer {
	position:absolute;
	bottom:0;
	margin-left: 20px;
}


table {
  font-family: 'Arial';
  margin: 25px auto;
  margin-top : 40px;
  border-collapse: collapse;
  border: 1px solid #eee;
  border-bottom: 2px solid #00cccc;
  tr {
     &:hover {
      background: #f4f4f4;
      
      td {
        color: #555;
      }
    }
  }
  th, td {
    color: #999;
    border: 1px solid #eee;
    padding: 12px 35px;
    border-collapse: collapse;
  }
  th {
    background: #00cccc;
    color: #fff;
    text-transform: uppercase;
    font-size: 12px;
    &.last {
      border-right: none;
    }
  }
}
 		
 	</style>

 	<style type="text/css" media = 'screen'>
	 	.mainContent {
	 		width: 80%;
	 		margin : 0 auto;
	 		height: 70%;
	 	}

	 	.container {
	 		margin-left: 10%;
	 	}
 	</style>
 </head>
 <body>

 	<div class="mainContent row">
 		<div class="heading">
	      <h2>Texknit Garments Limited</h2>
			<h3>Jhinuk Fashion</h3>
			<h4>Items Survey Report</h4>
			<h5><?php echo $showroom; ?></h5>
		</div>

		<div class="row">
		    <div class="col-md-11 item_table">

		    <div class="panel panel-default">
			  <div class="panel-heading">Survey Information</div>
			  <div class="panel-body">
			    	<p>Survey ID : <?php echo $survey_data->survey_id ?> </p>
			    	<p>Time : <?php echo $survey_data->created_at ?> </p>
			    	<p>User : <?php echo $survey_data->user ?> </p>
			    	<p>Total items : <?php echo $survey_data->total_items; ?> </p>
			    	<p>Missing items : <?php echo $survey_data->missing_items; ?> </p>
			  </div>
			</div>


		        <div class="tabbbleContainer">
		        	<p>Missing items details</p>
		            <table class="table table-bordered" id="transfer_table">
		            	<thead>
			                <th>Item id </th>
							<th>Color</th>
							<th>Size</th>
							<th>type</th>
							<th>Designer Style</th>
							<th>Price</th>
		                </thead>

		                <tbody>
		                	<?php foreach ($missing_items as $m) { ?>
							<tr>
								<td><?php echo $m->id ?></td>
								<td><?php echo $m->color_code; ?></td>
								<td><?php echo $m->size ?></td>
								<td><?php echo $m->type ?></td>
								<td><?php echo $m->designer_style ?></td>
								<td><?php echo $m->sell_price ?></td>
							</tr>
							<?php } ?>
						</tbody>
		            </table>
		            <a id="bottomOfDiv"></a>
		        </div>
		    </div>
		</div>
	</div>



    <div class="container showdesktop col-xs-10">
    	 
 	</div> 	

    <div class="col-xs-10 showprint signature">
        <p style="font-weight : 500">Authorized Signature</p>
        <p class="name"> <?php echo $name; ?></p>
        <p class="phone">Contact :  <?php echo $mobile; ?></p>
    </div>

 	</div>

		

 
 </body>
 </html>
