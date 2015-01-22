<style type="text/css">
      .row {
            padding-left : 10em;
            padding-right: 10em;
      }
</style>

<div class="row">

	<?php echo form_open("auth/create_group" , array('role' => 'form'));?>

	      <p>
	            <?php echo lang('create_group_name_label', 'group_name');?> <br />
	            <?php echo form_input($group_name);?>
	      </p>

	      <p>
	            <?php echo lang('create_group_desc_label', 'description');?> <br />
	            <?php echo form_input($description);?>
	      </p>

	      <p><?php echo form_submit('submit', lang('create_group_submit_btn'));?></p>

	<?php echo form_close();?>
</div>
<div id="infoMessage"><?php echo $message;?></div>