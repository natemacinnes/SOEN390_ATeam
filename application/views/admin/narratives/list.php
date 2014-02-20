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
	<?php echo form_open('admin/batchAction'); ?>
	<ul class="pagination float-left">
		<li><?php echo form_submit('delete', 'Delete', "class='btn btn-default'"); ?></li>
		<li><?php echo form_submit('publish', 'Publish', "class='btn btn-default'"); ?></li>
		<li><?php echo form_submit('unpublish', 'Unpublish', "class='btn btn-default'"); ?></li>
	</ul>

	<ul class="pagination float-right">
		<li><a href="#">&laquo;</a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">&raquo;</a></li>
	</ul>
	<div class="clear"></div>

	<table class="table table-hover">
		<thead>
			<tr>
				<th><a href="#" class="sort-btn active desc">ID</a></th>
				<th><a href="#" class="sort-btn">Length</a></th>
				<th><a href="#" class="sort-btn">Language</a></th>
				<th><a href="#" class="sort-btn">Created</a></th>
				<th><a href="#" class="sort-btn">Uploaded</a></th>
				<th><a href="#" class="sort-btn">Flags</a></th>
				<th><a href="#" class="sort-btn">Status</a></th>
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
						<?php echo anchor('admin/narratives/' . $narrative['narrative_id'] . '/review', '<span class="glyphicon glyphicon-eye-open"></span>', 'title="Review" class="btn btn-default btn-xs" role="button"'); ?>
						<?php echo anchor('admin/comments/' . $narrative['narrative_id'], '<span class="glyphicon glyphicon-comment">', 'title="View Comments" class="btn btn-default btn-xs" role="button"'); ?>
						<?php echo anchor('admin/narratives/' . $narrative['narrative_id'], '<span class="glyphicon glyphicon-pencil"></span>', 'title="Edit" class="btn btn-default btn-xs" role="button"'); ?>
						<?php echo anchor('admin/narratives/' . $narrative['narrative_id'] . '/delete', '<span class="glyphicon glyphicon-remove">', 'title="Delete" class="btn btn-default btn-xs" role="button"'); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	
	<!-- FIXME should be button anchor links and use JS to trigger submit buttons above -->
	<?php echo form_open('admin/batchAction'); ?>
	<ul class="pagination float-left">
		<li><?php echo form_submit('delete', 'Delete', "class='btn btn-default'"); ?></li>
		<li><?php echo form_submit('publish', 'Publish', "class='btn btn-default'"); ?></li>
		<li><?php echo form_submit('unpublish', 'Unpublish', "class='btn btn-default'"); ?></li>
	</ul>
	<ul class="pagination float-right">
		<li><a href="#">&laquo;</a></li>
		<li><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#">4</a></li>
		<li><a href="#">5</a></li>
		<li><a href="#">&raquo;</a></li>
	</ul>
	<div class="clear"></div>

</div>
