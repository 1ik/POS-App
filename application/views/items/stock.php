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
    <?php echo form_open('items/stock' , array('method' => 'GET' , 'role' => 'form', 'id'));?>

        <div class="form-group">
            <label for="showroom_checkbox">Designer style number : </label></label><br/>
            <input type="text" name="designer_style" />
        </div>

        <div class="form-group">
            <label for="sold_checkbox"> View Option </label></label><br/>
            <select name="display" class="form-control" >
                <option value="item-type">item type wise</option>
                <option value="size">size wise</option>
                <option value="color">color wise</option>
                <option value="style">designer style wise</option>
            </select>
        </div>

        <div class="form-group">
            <label for="showroom_checkbox">showroom </label></label><br/>
            <select name="showroom_id" class="form-control" >
                <option value="-1">select showroom</option>
                <?php foreach($showrooms as $showroom):?>
                    <option value="<?php echo $showroom->id; ?>"><?php echo $showroom->name; ?></option>
                <?php endforeach?>
            </select>
        </div>        

        <div class="form-group">
            <label for="sold_checkbox"> Status </label></label><br/>
            <select name="status" class="form-control" >
                <option value="sold">Sold</option>
                <option value="not_sold">Not sold</option>
                <option value="both">Both</option>
            </select>
        </div>        

        <div class="form-group">
            <input class="btn btn-default" type="submit" value="submit" />
        </div>
        <?php echo validation_errors();?>
    </div>
    
    <div class="col-md-8" style="margin-left  :10px" >
            <label class="strong"><span id="loader_item_store" class ="invisible" > <img src="<?php echo site_url('assets/img/transparent-ajax-loader.gif');?>" /> </span>Item stock :</label>
            <table class="table" id='item_store_table' >
                <tr>
                    <th>type</th>
                    <th>size</th>
                    <th>color</th>
                    <th>design</th>
                    <th>showroom</th>
                    <th>quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php if(isset($rows)): ?>
                <?php $total_quantity = 0; $total_price =0; ?>
                <?php foreach($rows as $row):?>
                    <tr>
                        <td><?php echo $row->type; ?></td>
                        <td><?php echo $row->size; ?></td>
                        <td><?php echo $row->color_code; ?></td>
                        <td><?php echo $row->designer_style; ?></td>
                        <td><?php echo $row->showroom; ?></td>
                        <td><?php echo $row->quantity; ?></td>
                        <?php $total_quantity += $row->quantity; ?>
                        <td><?php echo $row->price; ?></td>
                        <?php $price = $row->quantity*$row->price;?>
                        <td><?php echo $price ?></td>
                        <?php $total_price += $price; ?>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $total_quantity; ?></td>
                    <td><?php echo $total_price; ?></td>
                </tr>
                <?php endif; ?>
            </table>
    </div>
</div>



<?php form_close(); ?>

    </div>

</div>

<script type="text/javascript" src="<?php echo site_url('assets/js/item_stock/size.js');?>"></script>
