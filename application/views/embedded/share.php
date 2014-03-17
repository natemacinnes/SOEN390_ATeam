<?php echo form_open('ajax/share'); ?>
<div class="float-left">
	<?php echo form_input('Email', 'email'); ?>
</div>
<div class="float-right">
	<?php echo form_submit('submit', 'Share Narrative'); ?>
</div>
<?php form_close(); ?>
