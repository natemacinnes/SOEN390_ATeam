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
<script type="text/javascript">
	// Click handler: Comment (root level)
	jQuery(".action-comment-post").not('.comment-processed').addClass('comment-processed').click(function() {
		var narrative_id = jQuery('#new-comment-form input[name=narrative_id]').val();
		var url = yd_settings.site_url + "comments/reply/" + narrative_id;
		var formdata = jQuery("#new-comment-form").serialize();
		$.post(url, formdata)
			.success(function(data) {
				// Remove the 'no comment' message if it exists
				jQuery('.comments-wrapper .remove-me').remove();
				// Add the new comment, pre-rendered by the controller
				jQuery(data).prependTo('.comments-wrapper').hide().slideDown();
				jQuery("#new-comment").val('');
			})
			.fail(function() {
				alert("An error occurred while adding your comment. Please try again.");
			});
	});
	// Click handler: Comment (on comment)
	jQuery(".action-comment-reply").not('.comment-processed').addClass('comment-processed').click(function() {
		var narrative_id = jQuery('#new-comment-form input[name=narrative_id]').val();
		var parent_comment_id = jQuery(this).parents('.comment').attr('id').substring(8);
		var url = yd_settings.site_url + "comments/reply/" + narrative_id + '/' + parent_comment_id;
		var formdata = jQuery("#new-comment-form").serialize();
		$.post(url, formdata)
			.success(function(data) {
				// Remove the 'no comment' message if it exists
				jQuery('.comments-wrapper .remove-me').remove();
				// Add the new comment, pre-rendered by the controller
				jQuery(data).prependTo('.comments-wrapper').hide().slideDown();
				jQuery("#new-comment").val('');
			})
			.fail(function() {
				alert("An error occurred while adding your comment. Please try again.");
			});
	});
	// Click handler: Flag (on comment)
	jQuery(".action-comment-report").not('.comment-processed').addClass('comment-processed').click(function() {
		var comment_id = jQuery(this).parents('.comment').attr('id').substring(8);
		var url = yd_settings.site_url + "comments/flag/" + comment_id;
		var formdata = jQuery("#new-comment-form").serialize();
		$.post(url, formdata)
			.success(function(data) {
				jQuery("#new-comment").val('');
				alert("Thank you, this comment has been reported.");
			})
			.fail(function() {
				alert("An error occurred while reporting the comment. Please try again.");
			});
	});
</script>
