<div class="container fixed-margin">
  <div class="page-header">
    <h1>Narrative <small>Edit: <?php echo $narrative_id; ?></small></h1>
  </div>
  <div class="form-group row">
    <label for="publish_status" class="col-sm-1 control-label">Status: </label>
    <div class="col-sm-4">
      <select id="publish_status" class="form-control">
        <option>Published</option>
        <option>Unpublished</option>
      </select>
	  <a href="#" class="btn btn-default top-margin" role="button">Save</a>
    </div>
  </div>

  <div class="float-left big-left-margin big-right-margin">
		<h2>Narrative Info:</h2>
		<table class="table table-condensed table-striped  table-bordered">
			<tr>
				<td><h4>Number of views:</h4></td> <td><h4><?php echo $narrative['views']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Number of agrees:</h4></td> <td><h4><?php echo $narrative['agrees']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Number of disagrees:</h4></td> <td><h4><?php echo $narrative['disagrees']; ?></h4></td>
			</tr>
			<tr>	
				<td><h4>Number of shares:</h4></td> <td><h4><?php echo $narrative['shares']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Number of flags:</h4></td> <td><h4><?php echo $narrative['flags']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Audio length (seconds):</h4></td> <td><h4><?php echo $narrative['audio_length']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Created on:</h4></td> <td><h4><?php echo $narrative['created']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Uploaded on:</h4></td> <td><h4><?php echo $narrative['uploaded']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Uploaded by:</h4></td> <td><h4><?php echo $narrative['login']; ?></h4></td>
			</tr>
			<tr>
				<td><h4>Language:</h4></td><td><h4><?php echo $narrative['language']; ?></h4></td>
			</tr>
		</table>
  </div>
  <div class="player-wrapper big-top-margin big-left-margin float-left" id="narrative-<?php echo $narrative_id; ?>">
  <?php
    // Need to add path to narrative here and in the source of the video
    $path = 'uploads/' . $narrative_id . '/combined.mp3';

    if (file_exists($path)):
    ?>	
      <img src='' id='audio_image' alt='Audio image to accompany narrative' height='510' width='400'>
      <audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls'></audio></br>
      <span id='current-time'></span>
    <?php else: ?>
    <div style="width:400px; height:400px; border: 1px solid #333; border-radius: 4px;"><p style="color:#333; margin: 100px;">Video does not exist.</p></div>

  <?php endif; ?>

  </div>
  
  <div class="clear"></div>
  
  <!-- Nav tabs -->
  <ul class="nav nav-tabs big-top-margin top-border top-padding">
    <li class="active"><a href="#edit" data-toggle="tab">Edit</a></li>
    <li><a href="#comments" data-toggle="tab">Comments</a></li>
    <li><a href="#flags" data-toggle="tab">Flags</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="edit">
    	<div class="page-header">
	      <h2>Edit</h2>
	    </div>
    	<?php echo form_open('admin/narratives/' . $narrative_id . '/process', 'class="big-bottom-margin"'); ?>
	      <div class="float-left big-right-margin">
	        <h3>Soundtracks:</h3>
	        <?php
	      	for($i = 1; $i <= $narrative['trackCtr']; $i++)
	      	{
	      		echo '<div class="bottom-border bottom-margin">';
	      		echo form_checkbox('tracks[]', $narrative['trackName'][$i], FALSE);
	      		echo '<h4 class="display-inline left-margin">'.$narrative['trackName'][$i].'</h4>';
	      		echo '<audio class="display-inline left-margin" controls><source src="'.base_url().$narrative['trackPath'][$i].'" type="audio/mpeg"></audio>';
	          echo '</div>';
	      	}
	        ?>
	      </div>
	      <div class="float-left big-left-margin">
	        <h3>Pictures:</h3>
	        <?php
	      	for($i = 1; $i <= $narrative['picCtr']; $i++)
	      	{
	      		echo '<div class="display-inline-block right-margin">';
	      		echo form_checkbox('pics[]', $narrative['picName'][$i], FALSE);
	      		echo '<h4>'.$narrative['picName'][$i].'</h4>';
	      		echo '<img src="'.base_url().$narrative['picPath'][$i].'" alt="'.$narrative['picName'][$i].'" width="200" height="300">';
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
    </div>
    <div class="tab-pane" id="comments">
    	<div class="page-header">
	      <h2>Comments</h2>
	    </div>
    	<table class="table table-hover">
	    <thead>
	      <tr>
	        <th>ID</th>
	        <th>Narrative ID</th>
	        <th>Parent ID</th>
	        <th>Content</th>
	        <th>Created</th>
	        <th>Flags</th>
	        <th>Status</th>
	        <th>Actions</th>
	      </tr>
	    </thead>
	    <tbody>
	      <?php foreach ($comments as $comment): ?>
	        <tr>
	          <td><?php print $comment['comment_id']; ?></td>
	          <td><?php print $comment['narrative_id']; ?></td>
	          <td><?php print $comment['parent_comment']; ?></td>
	          <td><?php print $comment['body']; ?></td>
	          <td><?php print $comment['created']; ?></td>
	          <td><?php echo anchor('admin/comments/' . $comment['comment_id'] . '/review', $comment['flags']); ?></td>
	          <td>Published</td>
	          <td>
	            <a href="#" title="Delete" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a>
	          </td>
	        </tr>
	      <?php endforeach; ?>
	    </tbody>
	  </table>
    </div>
    <div class="tab-pane" id="flags">
    	<div class="page-header">
	      <h2>Flags</h2>
	    </div>
	    <table class="table table-hover">
	      <thead>
	        <tr>
	          <th>ID</th>
	          <th>Description</th>
	          <th>Date</th>
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
  </div>
</div>