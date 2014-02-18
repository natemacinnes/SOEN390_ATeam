
<div class="comments-container float-left">
	<div class="comments-wrapper">
		<div class="comment">
			<textarea class="form-control" rows="3" placeholder="Enter your comment..."></textarea>
			<a href="#" class="btn btn-primary btn-sm top-margin float-right" role="button">Post</a>
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