

<style type="text/css">

    .row {
        padding : 20px;
    }

    .infoMessage {
        text-align: center;
        font-weight: 600;
        font-size: 2.5em;
        margin-bottom: 0;
        padding-bottom: 0;
    }


    .info{
        text-align: center;
        margin : 0;
        padding : 0;
        font-size: 18px;
        font-weight: 600;
    }

    .formcontainer {
        margin-left: 200px;
    }
    th {
        text-align: center;
    }



</style>





<div class="row">

    <div class="col-md-8 formcontainer">

        <?php echo form_open('showroom/sell_info', array('method' => 'get', 'role' => 'form' , 'class' => 'form-inline')); ?>

            <div class="form-group" class="col-md-4">

                <input type="text" class="form-control date" id="from" placeholder="From" name="from_date" />

            </div>



            <div class="form-group" class="col-md-4">

                <input type="text" class="form-control date" id="to" placeholder="To" name="to_date" />

            </div>



            <div class="form-group" class="col-md-2">
                <select name="showroom_id" id="showroom" class="form-control" >
                    <option value="all">All Showrooms</option>
                    <?php foreach($showrooms as $showroom): ?>
                        <?php if($showroom->id != 1): ?>
                            <option value="<?php echo $showroom->id ?>"><?php echo $showroom->name ?></option>
                        <?php endif?>
                    <?php endforeach?>
                </select>
            </div>

            <div class="form-group" class="col-md-2">
                <select name="type" id="type" class="form-control" >
                    <option value="price">Price</option>
                    <option value="pieces">Pieces</option>
                </select>
            </div>



            <div class="form-group" class="col-md-4">

                <input type="submit" class="form-control btn-primary" id="to" value="check" />

            </div>



        <?php echo form_close(); ?> 

    </div>

</div>



<div class="row info">



    <?php if(isset($sell_data) && count($sell_data)){ ?>

        <div class="alert alert-success">

            <p><?php echo $sell_data['showroom_name']; ?> </p>

            <strong><?php echo $sell_data['from']; ?></strong> To <strong><?php echo $sell_data['to']; ?></strong>

        </div>

    <?php } else { ?>



        <div class="alert alert-warning">NO DATA</div>

    <?php } ?>



</div>



<div class="row">

    <div class="col-md-12">

        <div class="table table-responsive">

            <?php if(isset($reports)): ?>

            <table class="table-bordered table table-bordered">

                <tr>

                    <th>DATE  </th>

                    <th>Sold items</th>
                    <th>Sell amount</th>
                    <th>Returned items</th>
                    <th>Return amount</th>
                    <td>Discount</td>
                    <th>Total</th>

                <tr/>

                <?php $sold_item_total = 0; $sold_amount_total = 0; $return_item_total = 0; 
                $return_amount_total = 0; $grand_total=0; $grand_total_discount=0; ?>

                <?php foreach($reports as $report): ?>

                    <?php $total = 0; ?>

                    <tr>

                        <td><?php echo $report->date; ?>  </td>

                        <td><?php echo $report->sold_items; ?>  </td>

                        <td><?php echo $report->total_sale; ?>  </td>
                        <?php $total = $total + $report->total_sale; ?>

                        <?php if($report->return_item_count != NULL) { ?>
                            <td><?php echo $report->return_item_count; ?></td>
                            <td><?php echo $report->return_total_price; ?></td>
                            <?php $total = $total - $report->return_total_price; ?>
                        <?php } else { ?>
                            <td>0</td>
                            <td>0</td>
                        <?php } ?>
                        <td><?php echo $report->total_discount; ?></td>
                        <?php $total -= $report->total_discount; ?>
                        <?php $grand_total_discount += $report->total_discount; ?>


                        <td><?php echo $total; ?></td>
                        <?php $grand_total += $total; ?>

                    <tr/>

                    <?php 

                        $sold_item_total += $report->sold_items;
                        $sold_amount_total += $report->total_sale;

                        if($report->return_item_count == NULL) {
                            $return_item_total += $report->return_item_count;
                            $return_amount_total += $report->return_total_price;
                        }
                        

                    ?>

                <?php endforeach?>

                    <tr>

                        <td></td>
                        <td><?php echo $sold_item_total; ?></td>
                        <td><?php echo $sold_amount_total; ?></td>
                        <td><?php echo $return_item_total; ?></td>
                        <td><?php echo $return_amount_total; ?></td>
                        <td><?php echo $grand_total_discount;  ?></td>
                        <td><?php echo $grand_total; ?></td>

                    </tr>

            </table>

            <?php endif ?>

        </div>

    </div>

</div>