<div class="comments-container float-left">
	<div class="comments-wrapper">
		<div class="comment">
			<textarea class="form-control" rows="3" placeholder="Enter your comment..."></textarea>
			<a href="#" class="btn btn-primary btn-sm top-margin float-right" id="<?php echo $narrative_id; ?>" role="button">Post</a>
			<div class="clear"></div>
		</div>
	<?php if($comments == NULL): ?>
		<div class="comment">
			<p id="default">No comments have been added yet, be the first to respond!</p>
		</div>
	<?php else: ?>
		<?php echo $comments; ?>
	<?php endif; ?>
	</div>
</div>

<script type="text/javascript">
	//Post Comment
	jQuery(".btn.btn-primary.btn-sm.top-margin.float-right").click(function()
	{
		var text = jQuery('.form-control').val();
		//Verify that the comments section is not Empty
		if(text != "")
		{
			jQuery("#default").text("Posted");
			var narrative_id = $(this).attr('id');
			$('.comments-wrapper').after("<div class='comment'> <p id='default'>" + text + "</p> </div>");
			var url = yd_settings.site_url + "comments/add/" + narrative_id + "/" + text;
			$.get(url, function() {})
				.fail(function() { alert( "Error Comment was not Added" ); });
		}
		else
		{
			alert("Error: No text was typed in the comments. Cannot post an empty comment.");
		}
	});

	//Reply and Post Reply Handling
	jQuery(".btn.reply").click(function()
	{
		var text = jQuery(this).siblings('textarea').val();
		jQuery(this).siblings('textarea').val('');
		if(text != "")
		{
			var comment_id = $(this).siblings('.reply').attr('id');
			var narrative_id = $(this).parent().attr('id');
			alert("Result:" + narrative_id + "," + comment_id + "," + dateCreated + "," + text);
			$(this).parent().append("<div class='comment'> <p id='default'>" + text + "</p> </div>");
			var url = yd_settings.site_url + "comments/reply_to_comment/" + narrative_id + "/" + comment_id + "/" + text;
			$.get(url, function() {})
				.fail(function() { alert( "Error Comment was not added" ); });
		}
		else
		{
			alert("Error: No text was typed in the comments. Cannot post an empty comment.");
		}
	});
</script>
