


<style type="text/css">
      .row {
            padding-left : 10em;
            padding-right: 10em;
      }
</style>

<div class="row">

<?php echo form_open("auth/create_user" , array('role' => 'form'));?>
      <div class="form-group">
            <?php echo lang('create_user_fname_label', 'first_name');?> <br />
            <?php echo form_input($first_name);?>
      </div>

      <div class="form-group">
            <?php echo lang('create_user_lname_label', 'last_name');?> <br />
            <?php echo form_input($last_name);?>
      </div>

      <div class="form-group">
            <?php echo lang('create_user_company_label', 'company');?> <br />
            <?php echo form_input($company);?>
      </div>

      <div class="form-group">
            <?php echo lang('create_user_email_label', 'email');?> <br />
            <?php echo form_input($email);?>
      </div>

      <div class="form-group">
            <?php echo lang('create_user_phone_label', 'phone');?> <br />
            <?php echo form_input($phone);?>
      </div>

      <div class="form-group">
            <?php echo lang('create_user_password_label', 'password');?> <br />
            <?php echo form_input($password);?>
      </div>

      <div class="form-group">
            <?php echo lang('create_user_password_confirm_label', 'password_confirm');?> <br />
            <?php echo form_input($password_confirm);?>
      </div>

      <div class="form-group">
            <?php echo lang('create_user_location_label', 'location');?> <br />
            <?php echo form_input($location);?>
      </div>


      <div class="form-group"><?php echo form_submit('submit', lang('create_user_submit_btn'));?></div>

<?php echo form_close();?>
</div>

<div id="infoMessage"><?php echo $message;?></div>