<div class="container fixed-margin">
	<div class="page-header">
		<h1>Dashboard</h1>
	</div>

	<table class="table">
		<tbody>
			<tr>
				<td><?php echo anchor('admin/narratives', 'Narratives', "class='btn btn-default'"); ?></td>
				<td><?php echo anchor('admin/upload', 'Upload', "class='btn btn-default'"); ?></td>
				<td><?php echo anchor('admin/comments', 'Comments', "class='btn btn-default'"); ?></td>
				<td><?php echo anchor('admin/settings', 'Settings', "class='btn btn-default'"); ?></td>
			</tr>
		</tbody>
	</table>
</div>