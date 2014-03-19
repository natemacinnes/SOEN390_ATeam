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
					<a href="#" class="btn btn-default bookmark-btn">Bookmark <span class="yellow glyphicon glyphicon-bookmark"></span></a>
					<a href="#" class="btn btn-default share-btn">Share <span class="blue glyphicon glyphicon-user"></span></a>
				</div>
			</div>

			<div class="float-right">
				<div class="btn-group">
					<a href="#agree" class="btn btn-default">Agree <span class="green glyphicon glyphicon-thumbs-up"></span></a>
					<a href="#disagree" class="btn btn-default">Disagree <span class="red glyphicon glyphicon-thumbs-down"></span></a>
				</div>
			</div>
			<div class="clear"></div>
		</div>

		<div class="share-link">
			<div class="link-content" style="display: hidden;">
				<div class="input-group" style="width: 404px;">
				  <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span></span>
				  <input type="text" class="form-control"/ value="URL">
				  <span class="input-group-addon"><a href="#">send</a></span>
				</div>
			</div>
		</div>
	</div>
<?php else: ?>
	<p>Narrative does not exist.</p>
<?php endif; ?>

