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
</style>


<div class="row" >
    <div class="col-md-4 formcontainer">
    <?php echo form_open('' , array('type' => 'POST' , 'role' => 'form', 'id'));?>

        <div class="form-group">
            <label for="group"> select a group name :    </label><br/>
            <select name="group" id="group" class="form-control" >
                <?php foreach($groups as $group): ?>
                    <option value="<?php echo $group->id ?>"><?php echo $group->name ?></option>
                <?php endforeach?>
            </select>
        </div>


        <div class="form-group">
            <label for="sub_group"> select a sub group name : 
                <span id="loader_sub_group" class ="invisible" > <img src="<?php echo site_url('assets/img/transparent-ajax-loader.gif');?>" /> </span>   </label><br/>
            <select name="sub_group_id" id="sub_group" class="form-control" >

            </select>
        </div>

        <div class="form-group">
            <label for="item_type_id"> select Item type name : 
                <span id="loader_item_type" class ="invisible" > <img src="<?php echo site_url('assets/img/transparent-ajax-loader.gif');?>" /> </span>   </label><br/>
            <select name="item_type_id" id="item_type" class="form-control" >

            </select>
        </div>

        <div class="form-group">
            <label for="size_id"> select product size : 
                <span id="loader_size" class ="invisible" > <img src="<?php echo site_url('assets/img/transparent-ajax-loader.gif');?>" /> </span>   </label><br/>
            <select name="size_id" id="size_id" class="form-control" >

            </select>
        </div>

        <div class="form-group">
            <label for="color_code">  color : 
            <input class="form-control" type="text" id="color_code" />
        </div>

        <div class="form-group">
            <label for="color_code"> Designer style : 
            <input class="form-control" type="text" id="designer_style" />
        </div>


        <div class="form-group">
            <input class="btn btn-default" type="submit" value="submit" />
        </div>
        <?php echo validation_errors();?>
    </div>

    <div class="col-md-8" >
        <div class="col-md-8" >
            <label class="strong"><span id="loader_item_store" class ="invisible" > <img src="<?php echo site_url('assets/img/transparent-ajax-loader.gif');?>" /> </span>Items available in the following store : 
                </label>
            <table class="table col-md-12" id='item_store_table'>
                <tr>
                    <th>store name</th> <th>locatoin</th><th>number of items</th>
                </tr>
            </table>
        </div>
    </div>

</div>

<?php form_close(); ?>
    </div>
</div>
<script type="text/javascript" src="<?php echo site_url('assets/js/item_search/size.js');?>"></script>