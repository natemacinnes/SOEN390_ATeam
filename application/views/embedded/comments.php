
<div class="comments-container float-left">
	<div class="comments-wrapper">
		<div class="comment">
			<textarea class="form-control" rows="3" placeholder="Enter your comment..."></textarea>
			<a href="#" class="btn btn-primary btn-sm top-margin float-right" id="<?php echo $narrative_id; ?>" role="button">Post</a>
			<div class="clear"></div>
		</div>
	</div>
	<?php
		foreach($comments as $com)
		{
			echo "<div class='comment'>
					<a class='report' href='#'><span class='glyphicon glyphicon-flag'></span></a>
					<p>" . $com['body'] . "</p>
					<a class='reply' href='#'>Reply</a>
				</div>";
		}
	?>
</div>
<script type="text/javascript">
	jQuery('.btn btn-primary btn-sm top-margin float-right').click(function() 
	{
		var narrative_id = jQuery('.btn btn-primary btn-sm top-margin float-right').attr('id');
		var currentDate = new Date();
		var dateCreated = currentDate.getFullYear() + "-"
					+ (currentDate.getMonth() + 1) + "-"
					+ currentDate.getDate() + " " +
					+ currentDate.getHours() + ":"  
					+ currentDate.getMinutes() + ":" 
					+ currentDate.getSeconds();
		var text = jQuery('.form-control').val();
		var url = yd_settings.site_url + "comments/post_comment/" + narrative_id + "/NULL/" + dateCreated + "/" + text;
		$.get(url, function() { alert( "Comment was added to database. Sample: " + text); });
	}
</script>