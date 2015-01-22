<?php 
	 //var_dump($items);
	$from = "";
	$to = "";
	if(count($items) > 0) {
		$from = $items[0]['source'];
		$to = $items[0]['destination'];

    $query = $this->db->query('select * from users where id = 1');
    $result = $query->result();
    $result = $result[0];
    //var_dump($result);
    $name = $result->first_name." ".$result->last_name;
    $mobile = $result->phone;
	}
 ?>


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
 			<h4>Items Transfer Report</h4>
 			<h5><?php echo $from; ?> To  <?php echo $to; ?></h5>
 			<?php date_default_timezone_set('Asia/Dhaka'); ?>

 			<h5>
        <?php print date("g:ia \\ l jS F Y" , strtotime($items[0]['transfer_time'])); ?>
 			</h5>
 		</div>





    <div class="container showdesktop col-xs-10">
      <p id="transfer_id">#<?php echo $transfer_id; ?></p>
      <table class="table table-bordered">
        <tr>
          <th>Type</th><th>Style</th> <th>Size</th> <th>Color </th> <th>Quantity</th> <th>Price</th><th>Total Price</th>
        </tr>
        <?php $total_price = 00; ?>
        <?php $total_quantity = 0; ?>
        <?php foreach ($items as $item): ?>
        <tr>
          <td><?php echo $item['item_type']?></td>
          <td><?php echo $item['designer_style']?></td>
          <td><?php echo $item['size']?></td>
          <td><?php echo $item['color']?></td>
          <td><?php echo $item['quantity']?></td>
          <td><?php echo $item['item_price']?></td>
          <td><?php echo $item['amount']?></td>
          <?php $total_price += $item['amount']; ?>
          <?php $total_quantity += $item['quantity']; ?>
        </tr>
        
        <?php endforeach; ?>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td><?php echo $total_quantity; ?></td>
          <td></td>
          <td><?php echo $total_price; ?></td>
        </tr>

      </table>

    </div>  

  <div class="col-xs-10 showdesktop signature">
        <p style="font-weight : 500">Authorized Signature</p>
        <p class="name"> <?php echo $name; ?></p>
        <p class="phone">Contact :  <?php echo $mobile; ?></p>
    </div>

  </div>



 		<div class="container showprint col-md-10">
 			<p id="transfer_id">#<?php echo $transfer_id; ?></p>
 			<table class="table table-bordered">
 				<tr>
 					<th>Type</th><th>Style</th> <th>Size</th> <th>Color </th> <th>Quantity</th> <th>Price</th><th>Total Price</th>
 				</tr>
 				<?php $total_price = 00; ?>
 				<?php $total_quantity = 0; ?>
        <?php $loop = 0; ?>
        <?php $first = true; ?>
 				<?php foreach ($items as $item): ?>
 				<tr>
 					<td><?php echo $item['item_type']?></td>
 					<td><?php echo $item['designer_style']?></td>
 					<td><?php echo $item['size']?></td>
 					<td><?php echo $item['color']?></td>
 					<td><?php echo $item['quantity']?></td>
 					<td><?php echo $item['item_price']?></td>
 					<td><?php echo $item['amount']?></td>
 					<?php $total_price += $item['amount']; ?>
 					<?php $total_quantity += $item['quantity']; ?>
 				</tr>
        <?php $loop++; ?>
        <?php if(($first && $loop == 20) || (!$first && $loop == 24)): ?>
          <?php $first = false; ?>
            </table>
            </div>
            <div class="row" style="margin-top : 20px; page-break-after: always; page-break-before: always"></div>
            <div class="container showprint col-md-10">
            <table class="table table-bordered">
              <tr>
                <th>Type</th><th>Style</th> <th>Size</th> <th>Color </th> <th>Quantity</th> <th>Price</th><th>Total Price</th>
              </tr>
              <?php $loop =0; ?>       
        <?php endif ?>
 				<?php endforeach; ?>
 				<tr>
 					<td></td>
 					<td></td>
 					<td></td>
 					<td></td>
 					<td><?php echo $total_quantity; ?></td>
 					<td></td>
 					<td><?php echo $total_price; ?></td>
 				</tr>
 			</table>
 		</div> 	

    <div class="col-xs-10 showprint signature">
        <p style="font-weight : 500">Authorized Signature</p>
        <p class="name"> <?php echo $name; ?></p>
        <p class="phone">Contact :  <?php echo $mobile; ?></p>
    </div>

 	</div>

		

 
 </body>
 </html>
