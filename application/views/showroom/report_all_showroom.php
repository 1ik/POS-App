

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Jhinuk Fashion</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    
   	<!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Put this into a custom JavaScript file to make things more organized -->

    <style type="text/css">
		.container {
			display: block;
			mrgin : 20 auto;
			text-align: center;
			padding-top: 30px;
			font-family: "Open Sans";
		}
		td {
			font-size: .8em;
		}

		h1,h2,h3,h4,h5,p {
			font-family: "Open Sans";
		}

		table {
			margin-top: 60px;
		}
    </style>
</head>

<body>
	
	<div class="container">
		<div class="row">
			<div class="row">
				<h2><?php echo $_GET['type'] == 'price' ? "Sale Price" : "Sold Pieces"; ?> Report from <?php echo $_GET["from_date"] ?> to <?php echo $_GET["to_date"]; ?></h2>
			</div>
			<div class="table table-responsive">
				<table class="table-bordered">
					<?php $showroom_worth = array(); ?>
					<?php $days_worth = array(); ?>
					<thead>
						<td></td>
						<?php foreach ($showrooms as $showroom): ?>
							<?php if($showroom->id != 1) { ?>
								<th><?php echo $showroom->name; ?></th>
								<?php $showroom_worth[$showroom->name] = 00; ?>
							<?}?>
						<?php endforeach?>
						<th>Total</th>
					</thead>

					<tbody>
						<?php foreach ($reports as $day => $report_array): ?>
							
							<tr>
								<th style="min-width : 100px"><?php echo $day; ?></th>
								<?php $day_worth = 0;?>
								<?php foreach($showrooms as $showroom) { ?>
									<?php if($showroom->id == 1) continue; ?>
									<td>
										<?php if(isset($report_array[$showroom->name])) {?>
											<?php echo $report_array[$showroom->name]; ?>
											<?php $showroom_worth[$showroom->name] += $report_array[$showroom->name]; ?>
											<?php $day_worth += $report_array[$showroom->name]; ?>
										<?php } else {?>
											00
										<?php }?>
									</td>
								<?php } ?>
								<td><?php echo $day_worth; ?></td>
								<?php $day_worth=0;?>
							</tr>
						<?php endforeach ?>
					</tbody>
					<tfoot>
						<th>Total</th>
						<?php $total = 0; ?>
						<?php foreach ($showroom_worth as $name => $value): ?>
							<td><?php echo $value; ?></td>
							<?php $total += $value; ?>
						<?php endforeach?>
						<td><?php echo $total; ?></td>
					</tfoot>
				</table>
			</div>
		</div>

		<div class="footer">
			
		</div>
	</div>


</body>
</html>