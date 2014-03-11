<div class="container fixed-margin">
	<div class="page-header">
		<h1>Narratives <small>View All</small></h1>
	</div>

	<!--<div class="dropdown dropdown-margin2 float-left">
		<button class="btn dropdown-toggle " type="button" id="dropdownMenu1" data-toggle="dropdown">
			Filter By ... <span class="caret"></span>
		</button>
		<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Published</a></li>
			<li role="presentation"><a role="menuitem" tabindex="-1" href="#">Unpublished</a></li>
		</ul>
	</div>-->

	<!-- FIXME needs work for bulk actions and no form close -->
	<?php echo form_open('admin/narratives/bulk'); ?>
		<div class="btn-group big-top-margin float-left">
			<button type="button" class="btn btn-default" disabled="disabled">Batch Actions:</button>
			<?php echo form_button(array('name' => 'action', 'value' => 'publish', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-eye-open"></span> Publish', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'unpublish', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-eye-close"></span> Unpublish', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'download', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-cloud-download"></span> Download', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'delete', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-remove"></span> Delete', 'class' => "btn btn-default")); ?>
		</div>
		<div class="btn-group big-top-margin float-right">
			<button type="button" class="btn btn-default" disabled="disabled">Mark as:</button>
			<?php echo form_button(array('name' => 'action', 'value' => 'markFor', 'type' => 'submit', 'content' => 'For', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'markNeutral', 'type' => 'submit', 'content' => 'Neutral', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'markAgainst', 'type' => 'submit', 'content' => 'Against', 'class' => "btn btn-default")); ?>
		</div>
	<!-- WHY DOES THIS ONLY WORK WITHOUT FORM CLOSE? -->

	<!--THIS GENERATES PAGINATION-->
	<?php
		if (strlen($links)){
			echo $links;
		}
	?>
	<div class="clear"></div>

	<table class="table table-hover">
		<thead>
			<tr>
				<th></th>
				<th><a href="<?php echo site_url("admin/narratives/id/".(($sort_order == "asc" && $sort_by == "id") ? "desc" : "asc")."/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "id") ? " active $sort_order" : ""; ?>">ID</a></th>
				<th><a href="<?php echo site_url("admin/narratives/length/".(($sort_order == "asc" && $sort_by == "length") ? "desc" : "asc")."/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "length") ? " active $sort_order" : ""; ?>">Length</a></th>
				<th><a href="<?php echo site_url("admin/narratives/language/".(($sort_order == "asc" && $sort_by == "language") ? "desc" : "asc")."/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "language") ? " active $sort_order" : ""; ?>">Language</a></th>
				<th><a href="<?php echo site_url("admin/narratives/age/".(($sort_order == "asc" && $sort_by == "age") ? "desc" : "asc")."/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "age") ? " active $sort_order" : ""; ?>">Created</a></th>
				<th><a href="<?php echo site_url("admin/narratives/uploaded/".(($sort_order == "asc" && $sort_by == "uploaded") ? "desc" : "asc")."/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "uploaded") ? " active $sort_order" : ""; ?>">Uploaded</a></th>
				<th><a href="<?php echo site_url("admin/narratives/flags/".(($sort_order == "asc" && $sort_by == "flags") ? "desc" : "asc")."/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "flags") ? " active $sort_order" : ""; ?>">Flags</a></th>
				<th><a href="<?php echo site_url("admin/narratives/status/".(($sort_order == "asc" && $sort_by == "status") ? "desc" : "asc")."/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "status") ? " active $sort_order" : ""; ?>">Status</a></th>
				<th>Actions</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($narratives as $narrative): ?>
				<tr>
			<td><?php echo form_checkbox('narratives[]', $narrative['narrative_id'], FALSE); ?>
					<td><?php print $narrative['narrative_id']; ?></td>
					<td><?php printf('%d', $narrative['audio_length']/60); ?>:<?php printf('%02d', $narrative['audio_length']%60); ?></td>
					<td><?php print $narrative['language']; ?></td>
					<td><?php print $narrative['created']; ?></td>
					<td><?php print $narrative['uploaded']; ?></td>
					<td><?php echo anchor('admin/narratives/' . $narrative['narrative_id'] . '/review', $narrative['flags']); ?></td>
					<td><?php echo $narrative['status'] == 1 ? 'Published' : 'Not Published'; ?></td>
					<td>
						<?php echo anchor('admin/narratives/' . $narrative['narrative_id'], '<span class="glyphicon glyphicon-pencil"></span>', 'title="Edit" class="btn btn-default btn-xs" role="button"'); ?>
						<?php echo anchor('admin/narratives/' . $narrative['narrative_id'] . '/download', '<span class="glyphicon glyphicon-cloud-download">', 'title="Download" class="btn btn-default btn-xs" role="button"'); ?>
						<?php echo anchor('admin/narratives/' . $narrative['narrative_id'] . '/delete', '<span class="glyphicon glyphicon-remove">', 'title="Delete" class="btn btn-default btn-xs" role="button"'); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<!-- FIXME should be button anchor links and use JS to trigger submit buttons above -->
	<?php echo form_open('admin/narratives/bulk'); ?>
		<div class="btn-group big-top-margin float-left">
			<button type="button" class="btn btn-default" disabled="disabled">Batch Actions:</button>
			<?php echo form_button(array('name' => 'action', 'value' => 'publish', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-eye-open"></span> Publish', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'unpublish', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-eye-close"></span> Unpublish', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'download', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-cloud-download"></span> Download', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'delete', 'type' => 'submit', 'content' => '<span class="glyphicon glyphicon-remove"></span> Delete', 'class' => "btn btn-default")); ?>
		</div>
		<div class="btn-group big-top-margin float-right">
			<button type="button" class="btn btn-default" disabled="disabled">Mark as:</button>
			<?php echo form_button(array('name' => 'action', 'value' => 'markFor', 'type' => 'submit', 'content' => 'For', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'markNeutral', 'type' => 'submit', 'content' => 'Neutral', 'class' => "btn btn-default")); ?>
			<?php echo form_button(array('name' => 'action', 'value' => 'markAgainst', 'type' => 'submit', 'content' => 'Against', 'class' => "btn btn-default")); ?>
		</div>
	<!-- WHY DOES THIS ONLY WORK WITHOUT FORM CLOSE? -->

	<!--THIS GENERATES PAGINATION-->
	<?php
		if (strlen($links)){
			echo $links;
		}
	?>
	<div class="clear"></div>

</div>
