<div class="container fixed-margin">
  <div class="page-header">
    <h1>Admin <small>Login</small></h1>
  </div>
    <?php echo form_open('admin/login', array('class' => 'form-horizontal')); ?>
    <div class="form-group">
      <label for="email" class="col-sm-2 control-label">Email</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="email" placeholder="Email" value="<?php echo set_value('email'); ?>">
      </div>
    </div>
    <div class="form-group">
      <label for="password" class="col-sm-2 control-label">Password</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" name="password" placeholder="Password">
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Sign in</button>
      </div>
    </div>
    <?php echo form_close(); ?>

</div>
