	<div class="page-header">
		<h1>Narrative <small><?php echo $narrative_id; ?></small></h1>
	</div>

	<?php
	// Need to add path to narrative here and in the source of the video
	$path = 'uploads/' . $narrative_id . '/combined.mp3';
	$total_votes = (float)$narrative['agrees'] + (float)$narrative['disagrees'];
	if (file_exists($path)):
	?>
		<div class="player-wrapper float-left right-margin" id="narrative-<?php echo $narrative_id; ?>">
			<img src='' id='audio_image' alt='Audio image to accompany narrative' height='400' width='400' >
			<br />
			<audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls'></audio></br>
			<span id='current-time'></span>
			
			<div class="player-stats">
				<div class="float-left">
					<p><span class="glyphicon glyphicon-eye-open"></span> <?php echo $narrative['views']; ?></p>
				</div>
				<div class="float-right">
					<p><span class="green glyphicon glyphicon-thumbs-up"></span> <?php echo $narrative['agrees']; ?> <span class="red glyphicon glyphicon-thumbs-down"></span> <?php echo $narrative['disagrees']; ?></p>
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
						<a href="#" class="btn btn-default">Bookmark <span class="yellow glyphicon glyphicon-bookmark"></span></a>
						<a href="#" class="btn btn-default">Share <span class="blue glyphicon glyphicon-user"></span></a>
					</div>
				</div>
				<div class="float-right">
					<div class="btn-group">
						<a href="#" class="btn btn-default">Agree <span class="green glyphicon glyphicon-thumbs-up"></span></a>
						<a href="#" class="btn btn-default">Disagree <span class="red glyphicon glyphicon-thumbs-down"></span></a>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	<?php else: ?>
		Video does not exist.
	<?php endif; ?>
	
<script type="text/javascript">
	player_buttons();
</script>
