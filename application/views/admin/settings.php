<div class="container fixed-margin">
  <div class="page-header">
    <h1>Portal Settings</h1>
  </div>

  <?php echo form_open('admin/update_settings', array('class' => 'form-horizontal')); ?>
	<div class="form-group">
		<label for="topic" class="col-sm-2 control-label">Portal topic:</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="portal_topic" placeholder="Topic" value="<?php echo set_value('portal_topic', $portal_topic); ?>">
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Update</button>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
