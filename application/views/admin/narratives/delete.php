<div class="container fixed-margin">
	<div class="page-header">
		<?php if (isset($error)): ?>
			<h1>Delete Narrative</h1>
		<?php else: ?>
			<?php if (count($narratives) == 1): ?>
				<h1>Narrative <small>Delete: <?php echo $narratives[0]; ?></small></h1>
			<?php else: ?>
				<h1>Narrative <small>Batch Delete</small></h1>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php if (isset($error)): ?>
		The narrative requested does not exist.
	<?php else: ?>
		<?php if (count($narratives) == 1): ?>
			<h3>Narrative archival:</h3>
			<p>If you choose not to download the narrative archive before deletion, the narrative will be <strong>permanently</strong> lost after deletion.</p>
			<?php echo anchor('admin/narratives/' . $narratives[0] . '/download', 'Download', "class='btn btn-default'"); ?>
		<?php else: ?>
			<h3>Narrative archival:</h3>
			<p>If you choose not to download the narrative archive before deletion, the narrative will be <strong>permanently</strong> lost after deletion.</p>
			<?php echo form_open('admin/narratives/bulk/download', 'class="big-bottom-margin"'); ?>
				<input type='hidden' name='narratives' value="<?php echo htmlentities(serialize($narratives)); ?>" />
				<?php echo form_submit('submit', 'Download All', "class='btn btn-default'"); ?>
			<?php echo form_close(); ?>
		<?php endif; ?>

		<div class="clear"></div>
		<br/>

		<?php if (count($narratives) == 1): ?>
			<h3>Narrative deletion:</h3>
			<p>Deleting a narrative will <strong>irreversibly</strong> delete its voting & shared data. If you wish to retain this information, consider unpublishing instead.</p>
			<?php echo anchor('admin/narratives/' . $narratives[0] . '/processDelete', 'Delete', "class='btn btn-default'"); ?>
			<?php echo anchor('admin', 'Cancel', "class='btn btn-default'"); ?>
		<?php else: ?>
			<h3>Narrative deletion:</h3>
			<p>Deleting a narrative will <strong>irreversibly</strong> delete its voting & shared data. If you wish to retain this information, consider unpublishing instead.</p>
			<?php echo form_open('admin/narratives/bulk/delete', 'class="big-bottom-margin"'); ?>
			<input type='hidden' name='narratives' value="<?php echo htmlentities(serialize($narratives)); ?>" />
				<?php echo form_submit('submit', 'Delete All', "class='btn btn-default'"); ?>
				<?php echo anchor('admin', 'Cancel', "class='btn btn-default'"); ?>
			<?php echo form_close(); ?>
		<?php endif; ?>
	<?php endif; ?>
</div>
