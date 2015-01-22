<h1><?php echo lang('deactivate_heading');?></h1>
<p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>

<div class="row">
	

<?php echo form_open("auth/deactivate/".$user->id , array('role' => 'form'));?>

	  <div class="form-g">
	  	<?php echo lang('deactivate_confirm_y_label', 'confirm');?>
	    <input type="radio" name="confirm" value="yes" checked="checked" />
	    <?php echo lang('deactivate_confirm_n_label', 'confirm');?>
	    <input type="radio" name="confirm" value="no" />
	  </div>

	  <?php echo form_hidden($csrf); ?>
	  <?php echo form_hidden(array('id'=>$user->id)); ?>

	  <p><?php echo form_submit('submit', lang('deactivate_submit_btn'));?></p>

	<?php echo form_close();?>

</div>