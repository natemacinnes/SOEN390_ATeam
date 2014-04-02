<div class="container fixed-margin">
  <div class="page-header">
    <h1>Portal Settings</h1>
  </div>

  <?php echo form_open('admin/update_settings', array('class' => 'form-horizontal')); ?>
  <div class="form-group">
		<label for="topic" class="col-sm-2 control-label">Portal topic:</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="portal_topic" placeholder="Topic" value="<?php echo set_value('portal_topic', $portal_topic); ?>">
      <p class="help-block">The topic entered here is displayed to users at the top left corner of the portal homepage.</p>
		</div>
	</div>
  <div class="form-group">
    <label for="contact" class="col-sm-2 control-label">Contact email:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" name="email_address" placeholder="example@example.com" value="<?php echo set_value('email_address', $email_address); ?>">
      <p class="help-block">Users clicking the contact links will be directed to this email address.</p>
    </div>
  </div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Update</button>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
