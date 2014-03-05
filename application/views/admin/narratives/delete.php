<div class="container fixed-margin">
	<div class="page-header">
		<?php if(isset($error)): echo '<h1>Delete Narrative</h1>'; ?>
		<?php else: if(count($narratives) == 1): echo '<h1>Narrative <small>Delete:' . $narratives[0] . '</small></h1>'; ?>
		<?php else: echo '<h1>Narrative <small>Batch Delete</small></h1>'; ?>
		<?php endif; endif;?>
	</div>
	<?php if (isset($error)): ?> The narrative requested does not exist.
	<?php else: ?>
		<?php if(count($narratives) == 1): ?>
			<h3>Click to download narrative for archiving or possible future re-upload:</h3>
			<?php echo anchor('admin/narratives/' . $narratives[0] . '/download', 'Download', "class='btn btn-default'"); ?>
		<?php else: ?>
			<h3>Download narratives for archiving or possible future re-upload:</h3>
			
			<h4>Batch download:</h4>
			<?php echo form_open('admin/downloadAll', 'class="big-bottom-margin"'); ?>
			<input type='hidden' name='narratives' value="<?php echo htmlentities(serialize($narratives)); ?>" />
			<?php echo form_submit('submit', 'Download All', "class='btn btn-default'"); ?>
			<?php echo form_close(); ?>
			
			<h4>Narrative downloads:</h4>
			<?php foreach($narratives as $id) { echo '<p>' . anchor('admin/narratives/' . $id . '/download', 'Download Narrative #' . $id, "class='btn btn-default'") . '</p>'; } ?>
		<?php endif; ?>
		
		<div class="clear"></div>
		<br/>
		
		<?php if(count($narratives) == 1): ?>
			<h3>Click to delete narrative:</h3>
			<p>Note:</br>Even if you re-upload the narrative later the statistics on it (such as likes, dislikes, number of views...) are going to be permanently deleted.</br>If you wish to keep the data on the narrative, just unpublish the narrative instead of deleting it.</p>
			<?php echo anchor('admin/narratives/' . $narratives[0] . '/processDelete', 'Delete', "class='btn btn-default'"); ?>
			<?php echo anchor('admin', 'Cancel', "class='btn btn-default'"); ?>
		<?php else: ?>
			<h3>Click to delete all narratives:</h3>
			<p>Note:</br>Even if you re-upload the narratives later the statistics on them (such as likes, dislikes, number of views...) are going to be permanently deleted.</br>If you wish to keep the data on the narratives, just unpublish the narrative instead of deleting them.</p>
			
			<?php echo form_open('admin/deleteAll', 'class="big-bottom-margin"'); ?>
			<input type='hidden' name='narratives' value="<?php echo htmlentities(serialize($narratives)); ?>" />
			<?php echo form_submit('submit', 'Delete All', "class='btn btn-default'"); ?>
			<?php echo anchor('admin', 'Cancel', "class='btn btn-default'"); ?>
			<?php echo form_close(); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
