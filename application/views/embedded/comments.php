<div class="comments-container float-left">
	<div class="new-comment">
		<form method="POST" name="new-comment-form" id="new-comment-form">
			<input type="hidden" name="narrative_id" value="<?php echo $narrative_id; ?>" />
			<textarea class="form-control" rows="3" placeholder="Enter your comment..." id="new-comment" name="comment-text"></textarea>
			<a href="#" class="btn btn-primary btn-sm top-margin float-right action-comment-post" id="<?php echo $narrative_id; ?>" role="button">Post</a>
			<div class="clear"></div>
		</form>
	</div>
	<div class="comments-wrapper">
	<?php if($comments == NULL): ?>
		<div class="comment remove-me">
			<p>No comments have been added yet, be the first to respond!</p>
		</div>
	<?php else: ?>
		<?php echo $comments; ?>
	<?php endif; ?>
	</div>
</div>
<div class="clear"></div>
<script type="text/javascript">
	initialize_commenting();
</script>
