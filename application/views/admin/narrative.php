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
      </ul>

    </div><!-- /.navbar-collapse -->
  </div>
</nav>

<div class="container fixed-margin">
  <div class="page-header">
    <h1>Narrative <?php echo $narrative_id; ?></h1>
  </div>
  <p>Created on: <?php echo $created; ?></p>
  <p>Uploaded on: <?php echo $uploaded; ?></p>
  <p>Uploaded by: <?php echo $uploaded_by; ?></p>
  <p>Language: <?php echo $language; ?></p>
  <p>Number of views: <?php echo $views; ?></p>
  <p>Number of agrees: <?php echo $agrees; ?></p>
  <p>Number of disagrees: <?php echo $disagrees; ?></p>
  <p>Number of shares: <?php echo $shares; ?></p>
  <p>Number of flags: <?php echo $flags; ?></p>
  </br>
  <?php echo form_open('admin/editNarrative/'.$narrative_id); ?>
  <h3>Soundtracks:</h3>
  <?php
	for($i = 1; $i <= $trackCtr; $i++)
	{
		echo '</br>';
		echo form_checkbox('tracks[]', $trackName[$i], FALSE);
		echo '<h4>'.$trackName[$i].'</h4>';
		echo '<audio controls><source src="'.base_url().$trackPath[$i].'" type="audio/mpeg"></audio>';
	}
  ?>
  </br>
  </br>
  <h3>Pictures:</h3>
  <?php
	for($i = 1; $i <= $picCtr; $i++)
	{
		echo '</br></br>';
		echo form_checkbox('pics[]', $trackName[$i], FALSE);
		echo '<h4>'.$picName[$i].'</h4>';
		echo '<img src="'.base_url().$picPath[$i].'" alt="'.$picName[$i].'" width="200" height="300">';
	}
  ?>
  </br></br>
  <?php echo form_submit('submit', 'Delete', "class='btn btn-default'"); ?>
</div>