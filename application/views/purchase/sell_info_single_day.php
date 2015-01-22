
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
        margin-left: 260px;
    }
    td {
        text-align: right;
    }

</style>

<style type="text/css" media="print">
    .formcontainer{
        display: none;
    }

</style>



<div class="row">
    <div class="col-md-8 formcontainer">
        <?php echo form_open('showroom/sell_info_single_date', array('method' => 'get', 'role' => 'form' , 'class' => 'form-inline')); ?>

            <div class="form-group" class="col-md-4">
                <input type="text" class="form-control date" id="select_date" placeholder="Select date" name="select_date" />
            </div>

            <div class="form-group" class="col-md-4">
                <select name="showroom_id" id="showroom" class="form-control" >
                    <?php foreach($showrooms as $showroom): ?>
                        <?php if($showroom->id != 1): ?>
                            <option value="<?php echo $showroom->id ?>"><?php echo $showroom->name ?></option>
                        <?php endif?>
                    <?php endforeach?>
                </select>
            </div>

            <div class="form-group" class="col-md-4">
                <select name="display_type" id="display_type" class="form-control" >
                    <option value="memo">By Memo</option>
                    <option value="item">By Item</option>
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
            <p><strong><?php echo $sell_data['date']; ?></strong></p>
            <p> <?php echo $sell_data['type']; ?> </strong>
        </div>

    <?php } else { ?>

        <div class="alert alert-warning">NO DATA</div>
    <?php } ?>

</div>



<?php if(isset($memo_items)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="table table-responsive">
            <table class="table-bordered table table-bordered">
                <tr>
                    <th>Memo ID  </th>
                    <th>Memo Token</th>
                    <th>Time</th>
                    <th>Salesman</th>
                    <th>Items sold</th>
                    <th>Item Price</th>
                    <th>Items Returned</th>
                    <th>Return Amount</th>
                    <th>Generated Bill</th>
                    <th>Discount amount</th>
                    <th>#</th>
                <tr/>
                <?php 

                    $total_sold=0; $total_returned=0; $sold_price_total=0; $return_price_total=0;  $discount_total = 0;
                    $total_generated_bill = 0; 

                ?>
                <?php foreach ($memo_items as $memo_item) { ?>
                <tr>
                    <td> <?php echo $memo_item->id;?> </td>
                    <td> <?php echo $memo_item->token; ?></td>
                    <td> <?php echo $memo_item->time?> </td>
                    <td> <?php echo $memo_item->salesman?> </td>
                    <td> <?php echo $memo_item->sold_items?> </td>
                    <td> <?php echo $memo_item->cash_billed?> </td>
                    <td> <?php echo $memo_item->returned_items?> </td>
                    <td> <?php echo $memo_item->return_amount?> </td>

                    <td> <?php echo $memo_item->cash_billed - $memo_item->return_amount  ?> </td>

                    <td> <?php echo $memo_item->discount; ?> </td>

                    <th><?php echo anchor('showroom/memo/'.$memo_item->id."/detail" , 'detail' , array('target' => '_blank')); ?></th>
                    <?php
                        $total_sold += $memo_item->sold_items;
                        $total_returned += $memo_item->returned_items;
                        $sold_price_total += $memo_item->cash_billed;
                        $return_price_total += $memo_item->return_amount;
                        $discount_total += $memo_item->discount;
                        $total_generated_bill += ($memo_item->cash_billed - $memo_item->return_amount);
                    ?>                    
                <tr/>

                <?php }?>
                <tr>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $total_sold; ?></td>
                    <td><?php echo $sold_price_total; ?></td>
                    <td><?php echo $total_returned; ?></td>
                    <td><?php echo $return_price_total; ?></td>
                    <td> <?php echo $total_generated_bill; ?></td>
                    <td><?php echo $discount_total; ?></td>
                </tr>
            </table>
            
        </div>
    </div>
</div>
<?php endif; ?>


<?php if(isset($sold_items)): ?>
    <?php $sold_price_total = 0; ?>
    <div class="row">
    <div class="col-md-12">
        <strong>Sold Items</strong>
        <div class="table table-responsive">
            <table class="table-bordered table table-bordered">
                <tr>
                    <th>Memo ID  </th>
                    <th>Item ID</th>
                    <th>Items Type</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Designer style</th>
                    <th>Sell Price</th>
                    <th>#</th>
                <tr/>
                <?php foreach($sold_items as $sold_item): ?>
                    <tr>
                        <td><?php echo $sold_item->memo_id; ?></td>
                        <td><?php echo $sold_item->item_id; ?></td>
                        <td><?php echo $sold_item->item_type; ?></td>
                        <td><?php echo $sold_item->size; ?></td>
                        <td><?php echo $sold_item->color; ?></td>
                        <td><?php echo $sold_item->designer_style; ?></td>
                        <td><?php echo $sold_item->sell_price; ?></td>
                    </tr>
                    <?php $sold_price_total += $sold_item->sell_price; ?>
                <?php endforeach?>
                   <td>Total</td>
                   <td><?php echo count($sold_items); ?></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td><?php echo $sold_price_total; ?></td>
            </table>

            
        </div>
    </div>
</div>
<?php endif ?>



<?php if(isset($returned_items)): ?>
    <?php $return_price_total =0; ?>
    <div class="row">
    <div class="col-md-12">
        <strong>Returned Items</strong>
        <div class="table table-responsive">
            
            <table class="table-bordered table table-bordered">
                <tr>
                    <th>Memo ID  </th>
                    <th>Item ID</th>
                    <th>Items Type</th>
                    <th>Size</th>
                    <th>Color</th>
                    <th>Designer style</th>
                    <th>Sell Price</th>
                    <th>#</th>
                <tr/>
                <?php foreach($returned_items as $returned_item): ?>
                    <tr>
                        <td><?php echo $returned_item->memo_id; ?></td>
                        <td><?php echo $returned_item->item_id; ?></td>
                        <td><?php echo $returned_item->item_type; ?></td>
                        <td><?php echo $returned_item->size; ?></td>
                        <td><?php echo $returned_item->color; ?></td>
                        <td><?php echo $returned_item->designer_style; ?></td>
                        <td><?php echo $returned_item->sell_price; ?></td>
                    </tr>
                    <?php $return_price_total += $returned_item->sell_price; ?>
                <?php endforeach?>
                <td>Total</td>
                   <td><?php echo count($returned_items); ?></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td></td>
                   <td><?php echo $return_price_total; ?></td>
            </table>
            
        </div>
    </div>
</div>
<?php endif ?>



<?php if(isset($expenses)): ?>

<div class="row">
    <div class="col-md-10">
    <strong>Expense : </strong>
        <div class="table table-responsive">
            <table class="table-bordered table table-bordered">
                <tr>
                    <th>Reason  </th>
                    <th>Amount</th>
                    <th>Explanation </th>
                    <th>Time </th>
                    
                <tr/>
                    <?php $total_expense = 0; ?>
                    <?php foreach($expenses as $expense): ?>
                        <tr>
                            <td><?php print $expense->reason ?></td>
                            <td><?php print $expense->amount ?></td>
                            <td><?php print $expense->explanation ?></td>
                            <td> 
                                <?php //$dtime = new DateTime($expense->date); print $dtime->format("g:ia \\ l jS F Y"); ?>
                                <?php print date("g:ia \\ l jS F Y" , strtotime($expense->created_at)); ?>
                            </td>
                        </tr>
                        <?php $total_expense += $expense->amount; ?>
                    <?php endforeach ?>
                    <td>Total</td>
                    <td><?php echo $total_expense; ?></td>
            </table>
        </div>
    </div>
</div>
<?php endif ?>



<?php if(isset($sold_price_total) || isset($sold_price_total)): ?>
<div class="row">
    <div class="col-md-9">
        <strong>Final report </strong>
        <div class="table table-responsive">

            <table class="table-bordered table table-bordered" id="final-report-table">
                <tr>
                    <td>Sold Item Price</td> <td><?php echo $sold_price_total; ?></td>
                <tr/>
                <tr>
                    <td>Returned Item Price</td> <td>-<?php echo $return_price_total; ?></td>
                </tr>

                <tr>
                    <td>Total Discount given</td> <td>-<?php echo $discount_total; ?></td>
                </tr>

                <tr>
                    <td>Expense Total amount</td> <td>-<?php echo $total_expense; ?></td>
                </tr>
                <tr>
                    <td>CASH</td> <td> <?php echo $sold_price_total - ($return_price_total + $discount_total + $total_expense); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php endif ?>