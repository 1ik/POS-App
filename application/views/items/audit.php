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

    <!-- Add custom CSS here -->
    <link href="<?php echo base_url(); ?>assets/css/simple-sidebar.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/selectpicker/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">



        <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
    <!-- Put this into a custom JavaScript file to make things more organized -->

</head>

<body>


<table class="table table-bordered">

	<thead>
		<th>purchase id</th>
		<th>purchase time</th>
		<th>color</th>
		<?foreach ($sizes as $size) { ?>
			<th><?php echo $size->name;?></th>
		<?php } ?>
		<th>Total</th>
	</thead>
	<tbody>
		
		<?php $purchase_total = 0; ?>
		<?php foreach ($purchase_report as $purchase_id => $row) { ?>
				<?php $created_at = $row['created_at']; ?>
				<?php $report = $row['report']; ?>
				
				<?foreach($report as $color => $size_array) { ?>
				<tr>
					<td><?php echo $purchase_id; ?></td>
					<td><?php echo $created_at; ?></td>
					<td><?php echo $color; ?></td>
					<?php $total = 0;?>
					
					<?php foreach($sizes as $size) { ?>
						<td>
							<?php 
								if(isset($size_array[$size->name])) {
									echo $size_array[$size->name];

									if(!isset($size_total[$size->name])) {
										$size_total[$size->name] = 0;
									}

									$size_total[$size->name] += $size_array[$size->name];
									$total += $size_array[$size->name]; 
								} else {
									echo 0;
								}
							?>
						</td>
					<?php } ?>
					<td><?php echo $total?></td>
				</tr>
				<?php } ?>
				
		<?php } ?>
		<tr>
			<td>Total</td>
			<td></td>
			<td></td>
			<?php foreach($sizes as $size) { ?>
				<td><?php echo $size_total[$size->name]; ?></td>
				<?php $purchase_total += $size_total[$size->name]; ?>
			<?php } ?>
			<td><?php echo $purchase_total; ?></td>
		</tr>
	</tbody>
	
	<thead>
		<th style="text-align : center" colspan="<?php echo 4 + count($sizes); ?>">
			Transfer 
		</th>
	</thead>
	<thead>
		<th>
			Showroom Name
		</th>
	</thead>
	<tbody>
		<?php foreach ($transfer_report as $showroom_name => $reports) { ?>
			<?php foreach( $reports as $color => $size_array) { ?>
				<tr>
					<td><?php echo $showroom_name; ?></td>
					<td></td>
					<td><?php echo $color; ?></td>
					<?php $total = 0; ?>
					<?php foreach($sizes as $size) { ?>
					<td>
						<?php 
							if(isset($size_array[$size->name])) {
								echo $size_array[$size->name];
								$total += $size_array[$size->name]; 


								if(!isset($transfer_size_total[$size->name])) {
									$transfer_size_total[$size->name] = 0;
								}

								$transfer_size_total[$size->name] += $size_array[$size->name];

							} else {
								echo 0;
							}
						?>
					</td>
					<? } ?>
					<td> <?php echo $total; ?></td>
				</tr>
			<?php } ?>
		<?php } ?>
		<tr>
			<td>Total</td>
			<td></td>
			<td></td>
			<?php $transfer_total=0; ?>
			<?php foreach($sizes as $size) { ?>
				<?php if(isset($transfer_size_total[$size->name])) { ?>
					<td><?php echo $transfer_size_total[$size->name]; ?></td>
					<?php $transfer_total += $transfer_size_total[$size->name]; ?>
				<?php } else { ?>
					<td>0</td>
				<?php } ?>
			<?php } ?>
			<td><?php echo $transfer_total; ?></td>
		</tr>


	</tbody>


	<thead>
		<th style="text-align : center" colspan="<?php echo 4 + count($sizes); ?>">
			SALES 
		</th>
	</thead>
	<thead>
		<th>
			Showroom Name
		</th>
	</thead>
	<tbody>
		<?php foreach ($sales_report as $showroom_name => $reports) { ?>
			<?php foreach( $reports as $color => $size_array) { ?>
				<tr>
					<td><?php echo $showroom_name; ?></td>
					<td></td>
					<td><?php echo $color; ?></td>
					<?php $total = 0; ?>
					<?php foreach($sizes as $size) { ?>
					<td>
						<?php 
							if(isset($size_array[$size->name])) {
								echo $size_array[$size->name];
								$total += $size_array[$size->name]; 

								if(!isset($sales_size_total[$size->name])) {
									$sales_size_total[$size->name] = 0;
								}

								$sales_size_total[$size->name] += $size_array[$size->name];


							} else {
								echo 0;
							}
						?>
					</td>
					<? } ?>
					<td> <?php echo $total; ?></td>
				</tr>
			<?php } ?>
		<?php } ?>

		<tr>
			<td>Total</td>
			<td></td>
			<td></td>
			<?php $sales_total=0; ?>
			<?php foreach($sizes as $size) { ?>
				<?php if(isset($sales_size_total[$size->name])) { ?>
					<td><?php echo $sales_size_total[$size->name]; ?></td>
					<?php $sales_total += $sales_size_total[$size->name]; ?>
				<?php } else { ?>
					<?php $sales_size_total[$size->name] = 0; ?>
					<td>0</td>
				<?php } ?>
			<?php } ?>
			<td><?php echo $sales_total; ?></td>
		</tr>
	</tbody>

	<tbody>
		<tr>
			<td>Stock Total</td>
			<td></td>
			<td></td>
			<?php $total = 0; ?>
			<?php foreach($sizes as $size) { ?>
				<?php 
					$transfer = 0;
					if(isset($transfer_size_total[$size->name])) {
						$transfer = $transfer_size_total[$size->name];
					}
					$sale = 0;
					if(isset($sales_size_total[$size->name])) {
						$sale = $sales_size_total[$size->name];
					}

				 ?>
				

				<td><?php echo $transfer - $sale; ?></td>
				<?php $total += $transfer - $sale; ?>
			<?php } ?>
			<td><?php echo $total; ?></td>
		</tr>
	</tbody>


	<thead>
		<th style="text-align : center" colspan="<?php echo 4 + count($sizes); ?>">
			STOCK INFORMATOIN 
		</th>
	</thead>
	<thead>
		<th>
			Showroom Names
		</th>
	</thead>
	<tbody>
		<?php foreach ($stock_report as $showroom_name => $reports) { ?>
			<?php foreach( $reports as $color => $size_array) { ?>
				<tr>
					<td><?php echo $showroom_name; ?></td>
					<td></td>
					<td><?php echo $color; ?></td>
					<?php $total = 0; ?>
					<?php foreach($sizes as $size) { ?>
					<td>
						<?php 
							if(isset($size_array[$size->name])) {
								echo $size_array[$size->name];
								$total += $size_array[$size->name]; 

								if(!isset($sales_size_total[$size->name])) {
									$sales_size_total[$size->name] = 0;
								}

								$sales_size_total[$size->name] += $size_array[$size->name];


							} else {
								echo 0;
							}
						?>
					</td>
					<? } ?>
					<td> <?php echo $total; ?></td>
				</tr>
			<?php } ?>
		<?php } ?>
	</tbody>




</table>


<!-- Bootstrap core JavaScript -->

<script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("active");
    });
</script>
<script src="<?php echo base_url(); ?>assets/selectpicker/bootstrap-select.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.selectpicker').selectpicker();
    });
</script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/dp/css/datepicker.css" />
<script src="<?php echo base_url(); ?>assets/dp/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/utils.js"></script>
</body>
</html>