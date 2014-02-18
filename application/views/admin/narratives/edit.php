<div class="container fixed-margin">
  <div class="page-header">
    <h1><?php echo isset($error) ? 'Edit Narrative' : 'Narrative ' . $narrative_id; ?></h1>
  </div>
  <?php if (isset($error)): ?>
    The narrative requested does not exist.
  <?php else: ?>
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
    <?php echo form_open('admin/narratives/' . $narrative_id . '/process'); ?>
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
  		echo form_checkbox('pics[]', $picName[$i], FALSE);
  		echo '<h4>'.$picName[$i].'</h4>';
  		echo '<img src="'.base_url().$picPath[$i].'" alt="'.$picName[$i].'" width="200" height="300">';
  	}
    ?>
    </br></br>
    <?php echo form_submit('submit', 'Save changes', "class='btn btn-default'"); ?>
    <?php echo anchor('admin/narratives/' . $narrative_id . '/delete', 'Delete', "class='btn btn-default'"); ?>
  <?php endif; ?>
</div>
