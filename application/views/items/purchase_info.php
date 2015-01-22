<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Anik
 * Date: 10/11/13
 * Time: 9:37 AM
 * To change this template use File | Settings | File Templates.
 */

?>
<style type='text/css' >
.invisible{

    display : none;

}

td {
    text-align: left;
}

</style>


<div class="row" >
    <div class="col-md-3 formcontainer">
    <?php echo form_open('items/purchase_data' , array('method' => 'GET' , 'role' => 'form', 'id'));?>
		<div class="form-group">
			<input type="text" class="form-control date" id="to" placeholder="From" name="from_date">
		</div>

		<div class="form-group">
			<input type="text" class="form-control date" id="from" placeholder="To" name="to_date">
		</div>    

        <div class="form-group">
            <label for="showroom_checkbox">Designer style number : </label></label><br/>
            <input type="text" name="designer_style" />
        </div>
        <div class="form-group">
            <label for="showroom_checkbox">Color : </label></label><br/>
            <input type="text" name="color_code" />
        </div>

        <div class="form-group">
            <label for="sold_checkbox"> View Option </label></label><br/>
            <select name="display" class="form-control" >
                <option value="item-type">item type wise</option>
                <option value="color">color wise</option>
                <option value="style">designer style wise</option>
                <option value="date">Date wise</option>
                <option value="size">Size wise</option>
            </select>
        </div>        

        <div class="form-group">
            <input class="btn btn-default" type="submit" value="submit" />
        </div>
        <?php echo validation_errors();?>
    </div>
    <?php 
        $display = $this->input->get('display'); 
        $from_date = $this->input->get('from_date');
        $to_date = $this->input->get("to_date");
        $total_quantity = 0;
        $total_amount = 0;
    ?>
    <div class="col-md-8" style="margin-left  :10px" >
            <label class="strong"><span id="loader_item_store" class ="invisible" > <img src="<?php echo site_url('assets/img/transparent-ajax-loader.gif');?>" /> </span>
                    Item stock : 
                        <?php if(isset($display)) echo "showring ".$display. " wise"; ?>
                        <?php if(isset($from_date) && $from_date != '' && isset($to_date) && $to_date != ''):?>
                            From <?php echo $from_date; ?> to <?php echo $to_date; ?>
                        <?php endif;?>
                        </label>
            <table class="table" id='item_store_table' >
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>color</th>
                    <th>design</th>
                    <th>Size</th>
                    <th>quantity</th>
                    <th>Amount</th>
                </tr>
                
                <?php if(isset($rows)): ?>
                <?php $total_quantity = 0; $total_price =0; ?>
                <?php foreach($rows as $row):?>
                    <tr>    
                    	<td><?php if(isset($display) && $display == 'date') echo $row->purchase_date; ?></td>
                        <td><?php if(isset($display) && $display == 'item-type') echo $row->type; ?></td>
                        <td><?php if(isset($display) && $display == 'color') echo $row->color_code; ?></td>
                        <td><?php if(isset($display) && $display == 'style') echo $row->designer_style; ?></td>
                        <td><?php if(isset($display) && $display == 'size') echo $row->size; ?></td>
                        <td><?php echo $row->quantity; ?></td>
                        <td><?php echo $row->price; ?></td>
                        <?php $total_quantity += $row->quantity; $total_amount += $row->price; ?>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td>Total : </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $total_quantity; ?></td>
                    <td><?php echo $total_amount; ?></td>
                </tr>
                <?php endif; ?>
            </table>
    </div>
</div>



<?php form_close(); ?>

    </div>

</div>

<script type="text/javascript" src="<?php echo site_url('assets/js/item_stock/size.js');?>"></script>
