<div class="page-header">
	<div class="narrative-report-wrapper float-right">
		<a class="action-narrative-report" href="#">Flag Narrative <span class="glyphicon glyphicon-flag"></span></a>
	</div>
	<h1>Narrative <small><?php echo $narrative_id; ?></small></h1>
</div>

<?php
// Need to add path to narrative here and in the source of the video
$path = $this->config->item('site_data_dir') . '/' . $narrative_id . '/combined.mp3';
$total_votes = (float)$narrative['agrees'] + (float)$narrative['disagrees'];
if (file_exists($path)):
?>
	<?php include_once("application/analyticstracking.php"); ?>
	<div class="player-wrapper float-left right-margin" id="narrative-<?php echo $narrative_id; ?>">
		<img src='' id='audio_image' alt='Audio image to accompany narrative' height='400' width='400' >
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
			<div class="clear"></div>
			<div class="progress">
			  <div class="progress-bar progress-bar-success" style="width: <?php echo round($narrative['agrees']/$total_votes * 100); ?>%">
				<span class="sr-only"><?php echo round($narrative['agrees']/$total_votes * 100); ?>% Complete (success)</span>
			  </div>
			  <div class="progress-bar progress-bar-danger" style="width: <?php echo round($narrative['disagrees']/$total_votes * 100); ?>%">
				<span class="sr-only"><?php echo round($narrative['disagrees']/$total_votes * 100); ?>% Complete (danger)</span>
			  </div>
			</div>
		</div>

		<div class="player-buttons bottom-margin">
			<div class="float-left">
				<div class="btn-group">
					<a href="#" class="btn btn-default bookmark-btn" role="button" title="Bookmark / ..." data-toggle="tooltip" data-placement="top" data-container="body">Bookmark <span class="yellow glyphicon glyphicon-bookmark"></span></a>
					<a href="#" class="btn btn-default share-btn" role="button" title="Share / Partager" data-toggle="tooltip" data-placement="top" data-container="body">Share <span class="blue glyphicon glyphicon-share"></span></a>
				</div>
			</div>

			<div class="float-right">
				<div class="btn-group">
					<a href="#agree" class="btn btn-default" role="button" title="Agree / ..." data-toggle="tooltip" data-placement="top" data-container="body">Agree <span class="green glyphicon glyphicon-thumbs-up"></span></a>
					<a href="#disagree" class="btn btn-default" role="button" title="Disagree / ..." data-toggle="tooltip" data-placement="top" data-container="body">Disagree <span class="red glyphicon glyphicon-thumbs-down"></span></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="share-link">
			<div class="link-content" style="display: none;">
				<div class="input-group" style="width: 404px;">
				  <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span>
				  <input type="text" style="resize: none;" class="form-control"/ value="<?php echo base_url("narratives/" . $narrative_id); ?>">
				  <span class="input-group-addon"><a id="copy-share" data-clipboard-text="<?php echo base_url("narratives/" . $narrative_id); ?>" title="copy">copy</a></span>
				</div>
				<p><small><span class="italic grey">*Copy the text to share or save / Copier le text pour partager ou sauver</span></small></p>
			</div>
		</div>
	</div>
<?php else: ?>
	<p id="DNE">Narrative does not exist.</p>
<?php endif; ?>

