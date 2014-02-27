
	<h1>Media Player</h1>
	<p>You selected narrative #<?php echo $narrative_id; ?>.</p>

	<?php
	// Need to add path to narrative here and in the source of the video
	$path = 'uploads/' . $narrative_id . '/combined.mp3';
	if (file_exists($path)):
	?>
		<div class="player-wrapper float-left right-margin" id="narrative-<?php echo $narrative_id; ?>">
			<img src='' id='audio_image' alt='Audio image to accompany narrative' height='400' width='400' >
			<br />
			<audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls'></audio></br>
			<span id='current-time'></span>
		</div>
	<?php else: ?>
		Video does not exist.
	<?php endif; ?>
