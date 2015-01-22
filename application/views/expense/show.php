<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Anik
 * Date: 10/10/13
 * Time: 11:25 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<style type="text/css">

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
        text-align: center;
    }
    th {
        text-align: center;
    }



</style>



<div class="row formcontainer">

    <div class="row">

        <?php echo form_open('expense', array('method' => 'get', 'role' => 'form' , 'class' => 'form-inline')); ?>

        <div class="form-group" class="col-md-4">
            <input required type="text" class="form-control date" id="from" placeholder="From" name="from_date" />
        </div>
        <div class="form-group" class="col-md-4">
            <input required type="text" class="form-control date" id="from" placeholder="To" name="to_date" />
        </div>

        <div class="form-group" class="col-md-2">

            <select name="expense_type_id" id="showroom" class="form-control" >
                    <option value="all">All</option>
                <?php foreach($expense_types as $type): ?>
                    <option value="<?php echo $type->id ?>"><?php echo $type->reason ?></option>
                <?php endforeach?>
            </select>
        </div>


        <div class="form-group" class="col-md-2">
            <select name="showroom_id" id="showroom" class="form-control" >
                <?php if(is_a_staff()): ?>
                    <option value="all">All Showrooms</option>
                <?php endif ?>
                <?php foreach($showrooms as $showroom): ?>
                    <?php if($showroom->id != 1): ?>
                        <?php if(is_a_staff() || get_user()->showroom_id == $showroom->id): ?>
                            <option value="<?php echo $showroom->id ?>"><?php echo $showroom->name ?></option>
                        <?php endif ?>
                    <?php endif?>
                <?php endforeach?>
            </select>
        </div>



        <div class="form-group" class="col-md-4">

            <input type="submit" class="form-control btn-primary" id="to" value="check" />

        </div>



        <?php echo form_close(); ?>

    </div>

</div>




<div class="row">
    <div class="col-md-10">
        <div class="table table-responsive tableContainer">
            <table class="table-bordered table table-bordered">
                <tr>
                    <th># </th>
                    <th>Type  </th>
                    <th>Showroom name</th>
                    <th>Amount</th>
                    <th>Time </th>
                    <th>Explanation </th>
                    <th>Added by</th>
                    <?php if(is_a_staff()): ?>
                        <th colspan="2"> Actions </th>
                    <?php endif ?>
                <tr/>
                <?php if(isset($expenses)): ?>
                    <?php $index = 1; ?>
                    <?php $total=0; ?>
                    <?php foreach($expenses as $expense): ?>
                        <tr>
                            <td><?php echo $index++;?></td>
                            <td><?php print $expense->type ?></td>
                            <td><?php print $expense->showroom ?></td>
                            <td><?php print $expense->amount ?></td>
                            <td>
                                <?php //$dtime = new DateTime($expense->date); print $dtime->format("g:ia \\ l jS F Y"); ?>
                                <?php print date("g:ia \\ l jS F Y" , strtotime($expense->created_at)); ?>
                            </td>
                            <td><?php print $expense->explanation ?></td>
                            <td><?php print $expense->username ?></td>
                            <?php if(is_a_member()): ?>
                                <td><a href="<?php echo base_url(); ?>expense/edit/<?php echo $expense->id; ?>">Edit</a></td>
                                <td><a href="<?php echo base_url(); ?>expense/delete/<?php echo $expense->id; ?>">Del</a></td>
                            <?php endif?>
                            <?php $total += $expense->amount; ?>
                        </tr>
                    <?php endforeach ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td><?php echo $total; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                <?php endif ?>
            </table>
        </div>
    </div>
</div>