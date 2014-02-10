<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Admin Panel</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Narratives <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><?php echo anchor('viewnarratives', 'View All'); ?></li>
            <li><?php echo anchor('admin/upload', 'Upload'); ?></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Comments <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><?php echo anchor('viewcomments', 'View All'); ?></li>
          </ul>
        </li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div>
</nav>


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
