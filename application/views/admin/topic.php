<div class="container fixed-margin">
  <div class="page-header">
    <h1>Change Portal Topic</h1>
  </div>
 
 
  
  
  <?php echo form_open('admin/changeTopic', array('class' => 'form-horizontal')); ?>
	<div class="form-group">
		<label for="topic" class="col-sm-2 control-label">Enter New Portal Topic:</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="topic" placeholder="Topic" value="<?php echo set_value('topic'); ?>">
		</div>
	</div>
	
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Submit Topic</button>
		</div>
	</div>
	<?php echo form_close(); ?>
      



 
</div>
