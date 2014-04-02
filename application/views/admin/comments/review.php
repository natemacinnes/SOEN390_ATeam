<div class="container fixed-margin">
	<div class="page-header">
		<h1>Comment <?php echo $comment['comment_id']; ?> <small>Review</small></h1>
	</div>

	<div class="float-left big-right-margin">
		<p><?php echo xss_clean($comment['flags']); ?> users have flagged this comment.</p>
		<div class="comment">
			<?php if ($comment['parent_comment'] && isset($parent_comment['body'])): ?>
				<div class="quote">
					<p><?php echo xss_clean($parent_comment['body']); ?></p>
				</div>
			<?php endif; ?>
			<p><?php echo $comment['body']; ?></p>
		</div>
		<div class="top-margin">
			<?php echo anchor("admin/comments/" . $comment['comment_id'] . "/delete", '<span class="glyphicon glyphicon-remove"></span> Delete', 'title="Delete" class="btn btn-default" role="button"'); ?>
      <?php if ($comment['flags']): ?>
        <?php echo anchor("admin/comments/" . $comment['comment_id'] . "/dismiss_flags?destination=" . uri_string(current_url()), '<span class="glyphicon glyphicon-ok-circle"></span> Dismiss flags', 'title="Dismiss flags" class="btn btn-default" role="button"'); ?>
      <?php endif; ?>
		</div>
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

</div>
