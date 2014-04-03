<div class="comments-container float-left">
	<div class="new-comment">
		<form method="POST" name="new-comment-form" id="new-comment-form">
			<input type="hidden" name="narrative_id" value="<?php echo $narrative_id; ?>" />
			<textarea class="form-control new-comment" rows="3" style="resize: none;" placeholder="Enter your comment / Entrer votre commentaire..." name="comment-text"></textarea>
			<a href="#" class="btn btn-primary btn-sm top-margin float-right action-comment-post" id="<?php echo $narrative_id; ?>" role="button">Post / Soumettre</a>
			<div class="clear"></div>
		</form>
	</div>
	<div class="comments-wrapper">
	<?php if($comments == NULL): ?>
		<div class="comment remove-me">
			<p>No comments have been added yet, be the first to respond!<br />Aucun commentaire n'a été ajouté, soyez le premier!</p>
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
