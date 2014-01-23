<html>
        <body>
                <?php echo form_open_multipart("admin/upload"); ?>
                        <?php echo form_label("Filename: ", "filename"); ?>
                        <?php echo form_upload(array('name' => 'userfile')); ?>
                        <?php echo form_submit('submit', 'Submit'); ?>
                <?php echo form_close(); ?>
        </body>
</html>
