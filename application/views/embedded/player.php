<div class="page-header">
	<div class="narrative-report-wrapper float-right">
		<a class="action-narrative-report" href="#">Report / D&eacute;noncer <span class="glyphicon glyphicon-flag"></span></a>
	</div>
	<h2>Narrative / Narratif <small><?php echo $narrative_id; ?></small></h2>
</div>

<?php
// Need to add path to narrative here and in the source of the video
$path = $this->config->item('site_data_dir') . '/' . $narrative_id . '/combined.mp3';
$total_votes = (float)$narrative['agrees'] + (float)$narrative['disagrees'];
if (file_exists($path)):
?>
	<div class="player-wrapper float-left right-margin" id="narrative-<?php echo $narrative_id; ?>">
		<img src='' id='audio_image' alt='Audio image to accompany narrative' height='300' width='400' >
		<br />
		<audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls' class="autoplay"></audio></br>
		<?php echo form_hidden('fullPlay', 'false'); ?>
		<?php echo form_hidden('opinion', 'null'); ?>
		<?php echo form_hidden('share', 'false'); ?>
		<?php echo form_hidden('bookmark', 'false'); ?>

		<div class="player-stats">
			<div class="float-left">
				<p><span class="glyphicon glyphicon-eye-open"></span> <?php echo $narrative['views']; ?>             <span class="bg-success success-message" style=""></span></p>
			</div>
			<div class="float-right">
				<p><span class="green glyphicon glyphicon-thumbs-up"></span> <span class="green text"><?php echo $narrative['agrees']; ?> </span><span class="red glyphicon glyphicon-thumbs-down"></span> <span class="red text"><?php echo $narrative['disagrees']; ?></span></p>
			</div>
			<div style="text-align: center;"><span class="glyphicon glyphicon-time"></span> <?php $datetime = explode(' ', $narrative['uploaded']); echo $datetime[0]; ?></div>
			<div class="clear"></div>
			<div class="progress">
			  <div class="progress-bar progress-bar-success" style="width: <?php echo round($narrative['agrees']/max($total_votes, 1) * 100); ?>%">
				<span class="sr-only"><?php echo round($narrative['agrees']/max($total_votes, 1) * 100); ?>% Complete (success)</span>
			  </div>
			  <div class="progress-bar progress-bar-danger" style="width: <?php echo round($narrative['disagrees']/max($total_votes, 1) * 100); ?>%">
				<span class="sr-only"><?php echo round($narrative['disagrees']/max($total_votes, 1) * 100); ?>% Complete (danger)</span>
			  </div>
			</div>
		</div>

		<div class="player-buttons bottom-margin">
			<div class="float-left">
				<div class="btn-group">
					<a href="#" class="btn btn-default disabled" role="button">Social / Sociaux: </a>
					<a href="#" class="btn btn-default bookmark-btn" role="button" title="Bookmark / Marque-page" data-toggle="tooltip" data-placement="top" data-container="body"><span class="yellow glyphicon glyphicon-bookmark"></span></a>
					<a href="#" class="btn btn-default share-btn" role="button" title="Share / Partager" data-toggle="tooltip" data-placement="top" data-container="body"><span class="blue glyphicon glyphicon-share"></span></a>
				</div>
			</div>

			<div class="float-right">
				<div class="btn-group">
					<a href="#" class="btn btn-default disabled" role="button">Vote / voter: </a>
					<a href="#agree" class="btn btn-default" role="button" title="Agree / D'accord" data-toggle="tooltip" data-placement="top" data-container="body"><span class="green glyphicon glyphicon-thumbs-up"></span></a>
					<a href="#disagree" class="btn btn-default" role="button" title="Disagree / Désaccord" data-toggle="tooltip" data-placement="top" data-container="body"><span class="red glyphicon glyphicon-thumbs-down"></span></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="share-link">
			<div class="link-content" style="display: none;">
				<div class="input-group" style="width: 404px;">
				  <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span>
				  <input type="text" style="resize: none;" class="form-control"/ value="<?php echo base_url("narratives/" . $narrative_id); ?>">
				  <span class="input-group-addon btn btn-default" id="copy-share" data-clipboard-text="<?php echo base_url("narratives/" . $narrative_id); ?>"><a title="copy">Copy</a></span>
				</div>
				<p><small><span class="italic grey">*Copy the text to share or save / Copier le text pour partager ou sauver</span></small></p>
			</div>
		</div>
	</div>
<?php else: ?>
	<p>Narrative does not exist / Le narratif indiqué n'existe pas.</p>
<?php endif; ?>

