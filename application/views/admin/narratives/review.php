<div class="container fixed-margin">
  <div class="page-header">
    <h1>Narrative <small>Review: <?php echo $narrative_id; ?></small></h1>
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
    /*$path = 'uploads/' . $narrative_id . '/combined.mp3';

    if (file_exists($path)):
    ?>
    <div class="player-wrapper" id="narrative-<?php echo $narrative_id; ?>">
      <img src='' id='audioImage' alt='audio_image' height='400' width='400'>
      <audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls'></audio></br>
      <span id='current-time'></span>
    </div>
    <?php else: ?>
    Video does not exist.
  <?php endif;*/ ?>

  
  <div class="flag-wrapper  float-left">
    <div class="page-header">
      <h2>Flags</h2>
    </div>
    <table class="table table-hover">
      <thead>
        <tr>
          <th><a href="#" class="sort-btn active desc">ID</a></th>
          <th><a href="#" class="sort-btn">Description</a></th>
          <th><a href="#" class="sort-btn">Date</a></th>
          <th>Remove</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($flags as $flag): ?>
          <tr>
            <td><?php print $flag['flag_id']; ?></td>
            <td><?php print $flag['description']; ?></td>
            <td><?php print $flag['date_created']; ?></td>
            <td>
              <a href="#" title="Delete" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="player-wrapper float-left">
    <div style="width:400px; height:400px; background:black; margin-top:60px;"><p style="color:#fff;">narrative placeholder</p></div>
    <a href="#" class="btn btn-default top-margin" role="button">Save</a>
    <a href="#" class="btn btn-default top-margin" role="button">Delete</a>
    <a href="#" class="btn btn-default top-margin" role="button">Back</a>
  </div>

</div>

