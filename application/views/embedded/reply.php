<div class="comment reply">
	<input type="hidden" class="parent-id" name="parent_id" value="<?php echo $parent_id; ?>" />
	<p class="quote"><?php echo $parent_body; ?></p>
	<form method="POST" name="new-reply-form" id="new-reply-form">
		<input type="hidden" name="narrative_id" value="" />
		<textarea class="form-control" rows="3" placeholder="Enter your Reply..." id="new-reply" name="comment-text"></textarea>
		<a href="#" class="btn btn-primary btn-sm top-margin float-right action-reply-post" id="<?php echo $parent_id; ?>" role="button">Reply</a>
		<div class="clear"></div>
	</form>
</div>