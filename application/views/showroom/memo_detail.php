
<style type="text/css" media="screen">
    .row {
        padding : 20px;
    }

    .memo-report{
        margin-bottom: 100px;
    }

    .infoMessage {
        text-align: center;
        font-weight: 600;
        font-size: 2.5em;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    td {
        text-align: right;
    }
    
    .formcontainer {
        margin-left: 300px;
    }
</style>
<style type="text/css" media="all">
    strong {
        text-align: center;
    }
    .info{
        text-align: center;
        margin : 0;
        padding : 0;
        font-size: 18px;
        font-weight: 600;
    }
</style>


<div class="row info">
    <div class="alert alert-success">
        <strong><?php echo "MEMO ID : ".$memo_data['memo_id']; ?> </strong>
        <p><?php echo $memo_data['showroom']; ?> </p>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
        <strong>Sold Items</strong>
        <div class="table table-responsive">
            <table class="table-bordered table table-bordered">
                <tr>
                    <th>Item ID  </th>
                    <th>Item Type</th>
                    <th>Item size</th>
                    <th>Item Color</th>
                    <th>Item Design</th>
                    <th>Item Sell Price</th>                 
                <tr/>
                <?php $sell_price=0;?>
                <?php foreach ($memo_items['solds'] as $memo_item) { ?>
                <tr>
                    <td><?php echo $memo_item->item_id; ?></td>
                    <td><?php echo $memo_item->item_type; ?></td>
                    <td><?php echo $memo_item->size; ?></td>
                    <td><?php echo $memo_item->color; ?></td>
                    <td><?php echo $memo_item->designer_style; ?></td>
                    <td><?php echo $memo_item->sell_price; ?></td>
                <tr/>
                <?php $sell_price += $memo_item->sell_price; ?>
                <?php } ?>
            </table>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-md-12">
    <strong>Returned Items</strong>
        <div class="table table-responsive">
            <table class="table-bordered table table-bordered">
                <tr>
                    <th>Item ID  </th>
                    <th>Item Type</th>
                    <th>Item size</th>
                    <th>Item Color</th>
                    <th>Item Design</th>
                    <th>Item Sell Price</th>                    
                <tr/>
                <?php $return_price =0; ?>
                <?php foreach ($memo_items['returns'] as $memo_item) { ?>
                <tr>
                    <td><?php echo $memo_item->item_id; ?></td>
                    <td><?php echo $memo_item->item_type; ?></td>
                    <td><?php echo $memo_item->size; ?></td>
                    <td><?php echo $memo_item->color; ?></td>
                    <td><?php echo $memo_item->designer_style; ?></td>
                    <td><?php echo $memo_item->sell_price; ?></td>
                <tr/>
                <?php $return_price += $memo_item->sell_price; ?>
                <?php } ?>
            </table>
        </div>
    </div>
</div>




<div class="row memo-report">
    <div class="col-md-12">
        <strong>Final Report</strong>
        <div class="table table-responsive">
            <table class="table-bordered table table-bordered">
                <tr>
                    <td> Sold Item's total price  </td> <td><?php echo $sell_price; ?>  </td>
                <tr/>
                <tr>
                    <td> Returned Item's total price </td> <td> - <?php echo $return_price; ?></td>
                </tr>    
                <tr>
                    <td>Discount</th> <td> - <?php echo $discount?></td>
                </tr>
                <tr>
                    <td>Bill Generated</td> <td><?php echo $sell_price - $return_price - $discount; ?></td>
                </tr>
                
                <tr></tr><tr></tr>
            </table>
        </div>
    </div>
</div>