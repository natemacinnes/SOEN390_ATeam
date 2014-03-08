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
      <?php echo form_hidden('modified', $modified); ?>
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
	<?php if(isset($deleted) && ($deleted['audioCtr'] != 0 || $deleted['imageCtr'] != 0)): ?>
		<?php echo form_open('admin/narratives/' . $narrative_id . '/restore', 'class="big-bottom-margin"'); ?>
      <?php echo form_hidden('modified', $modified); ?>
			<?php if(isset($deleted['audioCtr']) && $deleted['audioCtr'] != 0): ?>
				<div class="float-left right-margin">
					<h3>Deleted Soundtracks:</h3>
					<?php
						for($i = 1; $i <= $deleted['audioCtr']; $i++)
						{
							echo '<div class="bottom-border bottom-margin">';
							echo form_checkbox('tracks[]', $deleted['deletedAudio'][$i], FALSE);
							echo '<h4 class="display-inline left-margin">'.$deleted['deletedAudio'][$i].'</h4>';
							echo '<audio class="display-inline left-margin" controls><source src="'.base_url().$deleted['deletedAudioPath'][$i].'" type="audio/mpeg"></audio>';
							echo '</div>';
						}
					?>
				</div>
			<?php endif; ?>
			<?php if(isset($deleted['imageCtr']) && $deleted['imageCtr'] != 0): ?>
				<div class="float-left">
					<h3>Deleted Pictures:</h3>
					<?php
						for($i = 1; $i <= $deleted['imageCtr']; $i++)
						{
							echo '<div class="display-inline-block right-margin">';
							echo form_checkbox('pics[]', $deleted['deletedImage'][$i], FALSE);
							echo '<h4>'. $deleted['deletedImage'][$i].'</h4>';
							echo '<img src="'.base_url(). $deleted['deletedImagePath'][$i].'" alt="'.$deleted['deletedImage'][$i].'" width="200" height="300">';
							echo '</div>';
						}
					?>
				</div>
			<?php endif; ?>
			<div class="clear"></div>
			<br/>
			<?php echo form_submit('submit', 'Restore Selected', "class='btn btn-default'"); ?>
		<?php echo form_close(); ?>
	<?php endif; ?>
  <?php endif; ?>
</div>
