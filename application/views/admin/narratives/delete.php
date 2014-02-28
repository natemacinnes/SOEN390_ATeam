<div class="container fixed-margin">
	<div class="page-header">
		<h1><?php echo isset($error) ? 'Delete Narrative' : 'Narrative <small>Delete:' . $narrative_id . '</small>'; ?></h1>
	</div>
	<?php if (isset($error)): ?> The narrative requested does not exist.
	<?php else: ?>
		<h3>Click to download narrative:</h3>
		<?php echo anchor('admin/narratives/' . $narrative_id . '/download', 'Download', "class='btn btn-default'"); ?>
		<div class="clear"></div>
		<br/>
		<h3>Click to delete narrative:</h3>
		<?php echo anchor('admin/narratives/' . $narrative_id . '/processDelete', 'Delete', "class='btn btn-default'"); ?>
		<?php echo anchor('admin', 'Cancel', "class='btn btn-default'"); ?>
	<?php endif; ?>
</div>
