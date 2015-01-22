<html>

<head>

	<title></title>

	<link href="<?php echo base_url(); ?>assets/css/bootstrap.css" rel="stylesheet">

	<link href="<?php echo base_url(); ?>assets/fonts/fonts.css" rel="stylesheet">



	<style type="text/css">

		@media print {
			.page-break	{ display: block; page-break-before: always; }
		}
	

		.khop {
			margin-top: 0px;
			margin-bottom: 0px;
		}

	</style>



</head>

<body>





<style type="text/css">

	body {

		font-family: Arial;

	}



	.uplabel{

		font-style: bold;

		font-family: 'aliquamreg';

		font-size: 15px;

		margin-left: 10px;
		letter-spacing:2px;

		/*top : 4px; */
		position : relative;

	}





	.bclabel{

		margin-left: 10px;

		font-size: 10px;

	}




	.price{
		margin-left: 10px;
		font-size: 13px;
	}



	.date {

		font-size: 10px;

		font-style: italic;

		float: right;

		margin-right: 5px;

		top : 6px; position : relative;

	}



	.color_code {

		float: right;

		font-size: 10px;

		margin-right: 5px;
		margin-top : -10px;

	}



	.iteminfo{

		margin-left: 5px;

		font-size: 10px;

	}



	.designer_style{

		float: right;

		margin-right: 5px;

	}

	.col-xs-2{

		width: 17%;

		margin-top: 10.5px;

		margin-left: 2%;



	}





</style>





<?php $number =0;?>

<?php foreach($items as $item): ?>

	<?php $number += 1; ?>

	<?php

		$date = new DateTime($item->created_at);

		$date = $date->format('dym');

	?>


		<div class="col-xs-2 khop">

			<?php $barcode = str_pad($item->id, 12, '0', STR_PAD_LEFT); ?>

			<div class="row" >

				<span class="uplabel">JHINUK</span> <span class="date"><?php echo $date; ?></span>

			</div>

			<div class="row">

				<span class="bclabel" > <?php echo $item->item_name; ?> (<?php echo $item->size_name?>) <span class="designer_style"> <?php echo $item->designer_style; ?>  </span></span>

			</div>

			<div class="row">

				<img style="margin-left : 10px" alt="<?php echo $barcode; ?>" src="http://localhost/barcode/html/image.php?filetype=PNG&dpi=72&scale=0.5&rotation=0&font_family=Arial.ttf&font_size=8&text=<?php echo $barcode; ?>&thickness=20&start=NULL&code=BCGcode128" />

			</div>

			<!--1311000000000005 -->

			<div class="row">
			
				</span><span class="color_code"><?php echo $item->color_code; ?></span>

			</div>

			<div class="row">

				<span class="price" style="top : -2px; position : relative" >  TK. <span style="font-weight:600"><?php echo $item->sell_price." "; ?> </span>Inc.VAT </span>

				<!-- <span class="price" style="top : -5px; position : relative" ></span> -->

			</div>

		</div>
		<?php if($number ==50 || $number== 100 || $number == 150 || $number ==200 || $number== 250 || $number == 300 || $number ==350 || $number== 400 || $number == 450 || $number ==500 || $number== 550 || $number == 600 || $number ==650 || $number== 700 || $number == 750 || $number ==800 || $number== 850 || $number == 900 || $number ==950 || $number== 1000 || $number == 1050 || $number ==1100 || $number== 1150 || $number == 1200) { ?>
			<div class="row" style="page-break-after: always"></div>

		<?php } ?>

<?php endforeach?>

</body>

</html>