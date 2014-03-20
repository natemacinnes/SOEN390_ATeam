<div class="comment" id="comment-<?php echo $comment['comment_id']; ?>">
	<input type="hidden" class="comment-id" name="comment_id" value="<?php echo $comment['comment_id']; ?>" />
	<?php if($comment['parent_comment']): ?>
		<p class="quote" id="parent-<?php echo $comment['parent_comment']; ?>"><?php echo $comments[$comment['parent_comment']]['body']; ?>
	<?php endif; ?>
	<p class="comment-body"><?php echo $comment['body']; ?></p>
  <div class="pull-left">
	<span class="grey date">
	<?php 
		if(array_key_exists("created", $comment))
		{
			echo $comment['created'];
		}
	?>
	</span>
  </div>
  <div class="actions">
	<a class="action-comment-reply" href="#" style="display: none;"><span class="glyphicon glyphicon-comment"></span> Reply</a>
    <a class="action-comment-report" href="#" style="display: none;"><span class="glyphicon glyphicon-flag"></span></a>
  </div>
  <div class="clear"></div>
</div>
