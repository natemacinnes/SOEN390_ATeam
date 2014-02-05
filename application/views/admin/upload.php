<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Admin Panel</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Narratives <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><?php echo anchor('viewnarratives', 'View All'); ?></li>
            <li><?php echo anchor('admin/upload', 'Upload'); ?></li>
          </ul>
        </li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div>
</nav>

<div class="container fixed-margin">
  <div class="page-header">
    <h1>Narratives <small>Batch Upload</small></h1>
  </div>

  <!--<div style="position:relative;">
        <a class='btn btn-primary' href='javascript:;'>
            Choose File...
            <input type="file" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' name="file_source" size="40"  onchange='$("#upload-file-info").html($(this).val());'>
        </a>
        <span class='label label-info' id="upload-file-info"></span>
  </div>-->

  <?php echo form_open_multipart("admin/processUpload", array('class' => 'form-horizontal')); ?>
    <div class="form-group">
	  <?php if(isset($error_message)) echo $error_message; ?>
      <?php echo form_label('Select zip to upload:', 'userfile', array('class' => 'col-sm-2 control-label')); ?>
      <div class='col-sm-10'><?php echo form_upload(array('name' => 'userfile', 'class' => 'form-control')); ?></div>
    </div>
    <?php echo form_submit('submit', 'Submit', "class='btn btn-default'"); ?>
  <?php echo form_close(); ?>

</div>
