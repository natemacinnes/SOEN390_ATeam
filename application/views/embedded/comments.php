<body>
<div class="comments-container float-left">
	<div class="comments-wrapper">
		<div class="comment">
			<textarea class="form-control" rows="3" placeholder="Enter your comment..."></textarea>
			<a href="#" class="btn btn-primary btn-sm top-margin float-right" id="<?php echo $narrative_id; ?>" role="button">Post</a>
			<div class="clear"></div>
		</div>
	</div>
	<?php
		if($comments == NULL)
		{
			echo "<div class='comment'>
					<p id='default'>Comments show here</p>
				  </div>";
		}
		else
		{
			foreach($comments as $com)
			{
				echo "<div class='comment' id='" . $com['narrative_id'] . "'>
						<a class='report' href='#'><span class='glyphicon glyphicon-flag'></span></a>
						<p id='comment_body'>" . $com['body'] . "</p>
						<a class='reply' id='" . $com['comment_id'] . "' href='#'>Reply</a>
						<textarea class='form-control' rows='3' placeholder='Enter your reply...'></textarea>
						<a class='btn reply' id='" . $com['comment_id'] . "' href='#'>Post</a>
					</div>";
			}
		}
	?>
</div>
</body>
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
			var currentDate = new Date();
			var dateCreated = currentDate.getFullYear() + "-"
						+ (currentDate.getMonth() + 1) + "-"
						+ currentDate.getDate() + " " +
						+ currentDate.getHours() + ":"  
						+ currentDate.getMinutes() + ":" 
						+ currentDate.getSeconds();
			$('.comment_wrapper').after("<div class='comment'> <p id='default'>" + text + "</p> </div>");
			var url = yd_settings.site_url + "comments/post_comment/" + narrative_id + "/NULL/" + dateCreated + "/" + text;
			$.get(url, function() { alert( "Comment was added to database. Sample: " + text); })
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
			var currentDate = new Date();
			var dateCreated = currentDate.getFullYear() + "-"
						+ (currentDate.getMonth() + 1) + "-"
						+ currentDate.getDate() + " " +
						+ currentDate.getHours() + ":"  
						+ currentDate.getMinutes() + ":" 
						+ currentDate.getSeconds();
			alert("Result:" + narrative_id + "," + comment_id + "," + dateCreated + "," + text);
			$(this).parent().append("<div class='comment'> <p id='default'>" + text + "</p> </div>");
			var url = yd_settings.site_url + "comments/reply_to_comment/" + narrative_id + "/" + comment_id + "/" + dateCreated + "/" + text;	
			$.get(url, function() { alert( "Comment was added to database. Sample: " + text); })
				.fail(function() { alert( "Error Comment was not added" ); });
		}
		else
		{
			alert("Error: No text was typed in the comments. Cannot post an empty comment.");
		}
	});
</script>
