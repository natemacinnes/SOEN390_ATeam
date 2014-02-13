<div class="container fixed-margin">
  <div class="page-header">
    <h1>Narrative <small><?php echo $narrative_id; ?></small></h1>
  </div>

  <div class="form-group row">
    <label for="publish_status" class="col-sm-1 control-label">Status: </label>
    <div class="col-sm-4">
      <select id="publish_status" class="form-control">
        <option>Published</option>
        <option>Unpublished</option>
      </select>
    </div>
  </div>

  <?php
    // Need to add path to narrative here and in the source of the video
    $path = 'uploads/' . $narrative_id . '/combined.mp3';
    if (file_exists($path)):
    ?>
    <div class="player-wrapper" id="narrative-<?php echo $narrative_id; ?>">
      <img src='' id='audioImage' alt='audio_image' height='400' width='400'>
      <audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls' autoplay=''></audio></br>
      <span id='current-time'></span>
    </div>
    <?php else: ?>
    Video does not exist.
  <?php endif; ?>

</div>
