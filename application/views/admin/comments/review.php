<div class="container fixed-margin">
	<div class="page-header">
		<h1>Comment <?php echo $comment['comment_id']; ?> <small>Review</small></h1>
	</div>

	<div class="comment float-left big-right-margin">
		<p><?php echo $comment['body']; ?></p>
	</div>

	<?php
	$path = $this->config->item('site_data_dir') . '/' . $comment['narrative_id'] . '/combined.mp3';
	if (file_exists($path)):
	?>
		<div class="player-wrapper float-left" id="narrative-<?php echo $comment['narrative_id']; ?>">
			<img src='' id='audio_image' alt='Audio image to accompany narrative' height='400' width='400'>
			<br />
			<audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls'></audio></br>
		</div>
	<?php else: ?>
		<div style="width:400px; height:400px; border: 1px solid #333; border-radius: 4px;"><p style="color: #333; margin: 100px;">Narrative does not exist.</p></div>
	<?php endif; ?>

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
				<th><a href="#" class="sort-btn">Date</a></th>
				<th><a href="#" class="sort-btn">Reason</a></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($flags as $flag): ?>
				<tr>
					<td><?php print $flag['cflag_id']; ?></td>
					<td><?php print $flag['date_created']; ?></td>
					<td><?php print $flag['description']; ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

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
