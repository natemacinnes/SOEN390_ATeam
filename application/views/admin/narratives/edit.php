<div class="container fixed-margin">
  <div class="page-header">
    <h1><?php echo isset($error) ? 'Edit Narrative' : 'Narrative <small>Edit:' . $narrative_id . '</small>'; ?></h1>
  </div>
  <?php if (isset($error)): ?>
    The narrative requested does not exist.
  <?php else: ?>
	<p>Status: <?php echo $status == 1 ? 'Published' : 'Not Published'; ?>
    <p style="display: inline-block">
      Number of views: <?php echo $views; ?><br/>
      Number of agrees: <?php echo $agrees; ?><br/>
      Number of disagrees: <?php echo $disagrees; ?><br/>
      Number of shares: <?php echo $shares; ?><br/>
      Number of flags: <?php echo $flags; ?><br/>
    </p>
    <p style="display: inline-block">
      Audio length (seconds): <?php echo $length; ?><br/>
      Created on: <?php echo $created; ?><br/>
      Uploaded on: <?php echo $uploaded; ?><br/>
      Uploaded by: <?php echo $uploaded_by; ?><br/>
      Language: <?php echo $language; ?><br/>
    </p>
    </br>
    <?php echo form_open('admin/narratives/' . $narrative_id . '/process', 'class="big-bottom-margin"'); ?>
      <div class="float-left right-margin">
        <h3>Soundtracks:</h3>
        <?php
      	for($i = 1; $i <= $trackCtr; $i++)
      	{
      		echo '<div class="bottom-border bottom-margin">';
      		echo form_checkbox('tracks[]', $trackName[$i], FALSE);
      		echo '<h4 class="display-inline left-margin">'.$trackName[$i].'</h4>';
      		echo '<audio class="display-inline left-margin" controls><source src="'.base_url().$trackPath[$i].'" type="audio/mpeg"></audio>';
          echo '</div>';
      	}
        ?>
      </div>
      <div class="float-left">
        <h3>Pictures:</h3>
        <?php
      	for($i = 1; $i <= $picCtr; $i++)
      	{
      		echo '<div class="display-inline-block right-margin">';
      		echo form_checkbox('pics[]', $picName[$i], FALSE);
      		echo '<h4>'.$picName[$i].'</h4>';
      		echo '<img src="'.base_url().$picPath[$i].'" alt="'.$picName[$i].'" width="200" height="300">';
          echo '</div>';
      	}
        ?>
      </div>
      <div class="clear"></div>
      <br/>
      <?php echo form_submit('submit', 'Remove Selected', "class='btn btn-default'"); ?>
      <?php echo anchor('admin/narratives/' . $narrative_id . '/delete', 'Delete', "class='btn btn-default'"); ?>
    <?php echo form_close(); ?>
  <?php endif; ?>
</div>
