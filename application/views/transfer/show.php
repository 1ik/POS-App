<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Anik
 * Date: 10/16/13
 * Time: 6:40 PM
 * To change this template use File | Settings | File Templates.
 */

?>

<style type="text/css" media="screen">

	.container{

		margin-top: 10px;

	}

</style>







<style type="text/css" media="screen">

	.container{

		margin-top: 10px;

	}

</style>





<style type="text/css">

    .item_table{

        margin-left: 5%;

        margin-top : 5%;

    }

    .item_table p{

        font-weight: 600;

        font-size: 20px;

    }

    .formcontainer{

        margin-left: 2%;

    }

    .remove{

        cursor: pointer;

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



</style>









<div class="row">

    <div class="col-md-11 item_table">
        <div class="tabbbleContainer">
            <table class="table table-striped" id="transfer_table">
            	<thead>
	                <tr>
                        <th>Transfer Id</th>
                        <?php if( ! isset($type) ) { ?>
                            <th>HeadOffice to Showroom Transfer ID</th>
                        <?php } elseif ( isset($type) && $type == 'showroom_to_showroom' ) { ?>
                            <th>Showroom to Showroom Transfer ID</th>
                        <?php } else { ?>
                            <th>Customer Transfer ID</th>
                        <?php } ?>
                        <th>Transfer Time</th>
                        <th>Total Number of Items</th>
                        <th>Transferred from</th>
                        <th>Transferred to</th>
                        <th>#</th>
	                </tr>

                </thead>



                <tbody>

					<?php foreach($transfers as $transfer): ?>

						<tr>
							<td><?php echo $transfer->transfer_id; ?></td>
                            <td><?php echo $transfer->ho_shwrm_transfer_id; ?></td>
							<td><?php echo date_format(new DateTime($transfer->transfer_time) , "d-m-Y h:i:s A"); ?></td>
							<td><?php echo $transfer->number_of_items; ?></td>
							<td><?php echo $transfer->from_showroom; ?></td>
                            <td><?php echo $transfer->to_showroom; ?></td>
							<td><?php echo anchor('transfer/detail/'.$transfer->transfer_id, 'view detail'); ?></td>
                            <td><?php echo anchor('transfer/report/'.$transfer->transfer_id, 'view report' , array("target" => "_blank")); ?></td>

                            <?php if(isset($type) && $type == 'showroom_to_showroom'): ?>
                                <?php if(!$transfer->reached) { ?>
                                    <td><?php echo anchor('transfer/make_received/'.$transfer->transfer_id, '!'); ?></td>
                                <?php } else { ?>
                                    <td><?php echo anchor('transfer/make_unreceived/'.$transfer->transfer_id, 'reached' ); ?></td>
                                <?php }?>
                            <?php endif ?>
						</tr>
					<?php endforeach?>
				</tbody>

            </table>

            <a id="bottomOfDiv"></a>

        </div>



    </div>

</div>





</div>





