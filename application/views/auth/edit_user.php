
<div id="infoMessage"><?php echo $message;?></div>

<style type="text/css">
      .row {
            padding-left : 10em;
            padding-right: 10em;
      }
</style>

<div class="row">
<?php echo form_open(uri_string() , array('role' => 'form'));?>

      <div class="form-group">
            <?php echo lang('edit_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </div>

      <div class="form-group">
            <?php echo lang('edit_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </div>

      <div class="form-group">
            <?php echo lang('edit_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </div>

      <div class="form-group">
            <?php echo lang('edit_user_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </div>

      <div class="form-group">
            <?php echo lang('edit_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </div>

      <div class="form-group">
            <?php echo lang('edit_user_password_confirm_label', 'password_confirm');?><br />
            <?php echo form_input($password_confirm);?>
      </div>

      <div class="form-group">
            <?php echo lang('edit_user_location_label', 'location');?><br />
            <?php echo form_input($location);?>
      </div>


      <div class="form-group">
          <label for="showroom">Showroom : </label> <br />
          <select name="showroom_id">
              <?php foreach($showrooms as $showroom) { ?>
                  <option <?php if($user->showroom_id == $showroom->id) { ?> selected <?php } ?> value="<?php echo $showroom->id; ?>"><?php echo $showroom->name; ?></option>
              <?php } ?>
          </select>
      </div>



    <h3><?php echo lang('edit_user_groups_heading');?></h3>
	<?php foreach ($groups as $group):?>
	<label class="checkbox">
	<?php
		$gID=$group['id'];
		$checked = null;
		$item = null;
		foreach($currentGroups as $grp) {
			if ($gID == $grp->id) {
				$checked= ' checked="checked"';
			break;
			}
		}
	?>
	<input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
	<?php echo $group['name'];?>
	</label>
	<?php endforeach?>

      <?php echo form_hidden('id', $user->id);?>
      <?php echo form_hidden($csrf); ?>

      <p><?php echo form_submit('submit', lang('edit_user_submit_btn'));?></p>

<?php echo form_close();?>
