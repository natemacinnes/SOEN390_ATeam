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
