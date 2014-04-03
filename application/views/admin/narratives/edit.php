<div class="container fixed-margin">
  <div class="page-header">
    <h1>Narrative <small><?php echo $narrative['narrative_id']; ?></small></h1>
  </div>

  <div class="float-left big-left-margin big-right-margin">
    <table class="narrative-info table table-condensed table-striped table-bordered">
      <tr>
        <td class="data-label">Status (click to toggle):</td>
        <td class="data-value">
          <?php if ($narrative['status']) {
            echo anchor("admin/narratives/" . $narrative['narrative_id'] . "/unpublish", 'Published', array('class' => 'btn btn-default btn-xs active'));
          }
          else {
            echo anchor("admin/narratives/" . $narrative['narrative_id'] . "/publish", 'Unpublished', array('class' => 'btn btn-default btn-xs'));
          }
          ?>
        </td>
      </tr>
      <tr>
        <td class="data-label"><span class="glyphicon glyphicon-eye-open"></span> Views:</td>
        <td class="data-value"><?php echo $narrative['views']; ?></td>
      </tr>
      <tr>
        <td class="data-label"><span class="glyphicon glyphicon-thumbs-up"></span> Agrees:</td>
        <td class="data-value"><?php echo $narrative['agrees']; ?></td>
      </tr>
      <tr>
        <td class="data-label"><span class="glyphicon glyphicon-thumbs-down"></span> Disagrees:</td>
        <td class="data-value"><?php echo $narrative['disagrees']; ?></td>
      </tr>
      <tr>
        <td class="data-label"><span class="glyphicon glyphicon-share"></span> Shares:</td>
        <td class="data-value"><?php echo $narrative['shares']; ?></td>
      </tr>
      <tr>
        <td class="data-label"><span class="glyphicon glyphicon-flag"></span> Flags:</td>
        <td class="data-value"><?php echo $narrative['flags']; ?></td>
      </tr>
      <tr>
        <td class="data-label">Audio length (seconds):</td>
        <td class="data-value"><?php echo $narrative['audio_length']; ?></td>
      </tr>
      <tr>
        <td class="data-label">Created on:</td>
        <td class="data-value"><?php echo $narrative['created']; ?></td>
      </tr>
      <tr>
        <td class="data-label">Uploaded on:</td>
        <td class="data-value"><?php echo $narrative['uploaded']; ?></td>
      </tr>
      <tr>
        <td class="data-label">Uploaded by:</td>
        <td class="data-value"><?php echo $admin['login']; ?></td>
      </tr>
      <tr>
        <td class="data-label">Language:</td>
        <td class="data-value"><?php echo $narrative['language']; ?></td>
      </tr>
    </table>
  </div>
  <div class="float-left">
    <?php
    // Need to add path to narrative here and in the source of the video
    $path = $this->config->item('site_data_dir') . '/' . $narrative['narrative_id'] . '/combined.mp3';

    if (file_exists($path)):
    ?>
      <div class="player-wrapper" id="narrative-<?php echo $narrative['narrative_id']; ?>">
        <img src='' id='audio_image' alt='Audio image to accompany narrative' height='300' width='400'>
        <br />
        <audio id='narrative_audio' src='<?php print base_url() . $path; ?>' type='audio/mp3' controls='controls'></audio></br>
      </div>
    <?php else: ?>
      <div style="width:400px; height:400px; border: 1px solid #333; border-radius: 4px;"><p style="color:#333; margin: 100px;">Narrative does not exist.</p></div>
    <?php endif; ?>
  </div>

  <div class="clear"></div>

  <!-- Nav tabs -->
  <ul class="nav nav-tabs big-top-margin top-border top-padding">
    <li class="active"><a href="#edit" data-toggle="tab">Edit</a></li>
    <li><a href="#comments" data-toggle="tab">Comments</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div class="tab-pane active" id="edit">
      <?php echo form_open('admin/narratives/' . $narrative['narrative_id'] . '/process', 'class="big-bottom-margin form-horizontal"'); ?>
        <?php echo form_hidden('modified', $narrative['modified']); ?>
        <div class="soundtracks float-left big-right-margin">
          <h3>Soundtracks:</h3>
          <?php
          foreach ($paths['tracks'] as $i => $track)
          {
            echo '<div class="soundtrack-wrapper bottom-margin clear form-group">';
            echo '<label>' . form_checkbox('tracks[]', basename($track), FALSE) . ' ' . basename($track) . '</label>';
            echo '<audio class="soundtrack left-margin" controls="controls"><source src="' . base_url() . $track . '" type="audio/mpeg"></audio>';
            echo '</div>';
          }
          ?>
        </div>
        <div class="pictures float-left big-left-margin">
          <h3>Pictures:</h3>
          <?php
          foreach ($paths['pictures'] as $i => $picture)
          {
            echo '<div class="picture display-inline-block right-margin">';
            echo '<label>' . form_checkbox('pics[]', basename($picture), FALSE) . ' ' . basename($picture) . '</label>';
            echo '<img src="' . base_url() . $picture . '" alt="' . basename($picture) . '" width="200" height="300">';
            echo '</div>';
          }
          ?>
        </div>
        <div class="clear"></div>
        <br/>
        <?php echo form_submit('submit', 'Unpublish selected', "class='btn btn-default'"); ?>
        <?php echo anchor('admin/narratives/' . $narrative['narrative_id'] . '/delete', 'Delete Narrative', "class='btn btn-default confirm-delete'"); ?>
      <?php echo form_close(); ?>
      <?php if (count($deleted['tracks']) || count($deleted['pictures'])): ?>
        <?php echo form_open('admin/narratives/' . $narrative['narrative_id'] . '/restore', 'class="big-bottom-margin"'); ?>
          <?php echo form_hidden('modified', $narrative['modified']); ?>
          <?php if (count($deleted['tracks'])): ?>
            <div class="soundtracks float-left right-margin">
              <h3>Deleted Soundtracks:</h3>
              <?php
                foreach ($deleted['tracks'] as $i => $track)
                {
                  echo '<div class="soundtrack-wrapper bottom-margin clear form-group">';
                  echo '<label class="left-margin">' . form_checkbox('tracks[]', basename($track), FALSE) . ' ' . basename($track) . '</label>';
                  echo '<audio class="soundtrack left-margin" controls="controls"><source src="' . base_url() . $track . '" type="audio/mpeg"></audio>';
                  echo '</div>';
                }
              ?>
            </div>
          <?php endif; ?>
          <?php if (count($deleted['pictures'])): ?>
            <div class="pictures float-left left-margin">
              <h3>Deleted Pictures:</h3>
              <?php
                foreach ($deleted['pictures'] as $i => $picture)
                {
                  echo '<div class="picture display-inline-block right-margin">';
                  echo '<label>' . form_checkbox('pics[]', basename($picture), FALSE) . ' ' . basename($picture) . '</label>';
                  echo '<img src="' . base_url() . $picture . '" alt="' . basename($picture) . '" width="200" height="300">';
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
      <table class="table table-hover table-striped">
      <thead>
        <tr>
          <th>Comment ID</th>
          <th>Parent ID</th>
          <th>Content</th>
          <th>Created</th>
          <th>Flags</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($comments): ?>
          <?php foreach ($comments as $comment): ?>
            <tr>
              <td><?php print $comment['comment_id']; ?></td>
              <td><?php print $comment['parent_comment']; ?></td>
              <td><?php print xss_clean($comment['body']); ?></td>
              <td><?php print $comment['created']; ?></td>
              <td><?php echo anchor('admin/comments/' . $comment['comment_id'] . '/review', $comment['flags']); ?></td>
              <td>Published</td>
              <td>
                <?php echo anchor("admin/comments/" . $comment['comment_id'] . "/delete?destination=" . uri_string(current_url()), '<span class="glyphicon glyphicon-remove"></span>', 'title="Delete" class="btn btn-default btn-xs confirm-delete" role="button"'); ?>
                <?php if ($comment['flags']): ?>
                  <?php echo anchor("admin/comments/" . $comment['comment_id'] . "/dismiss_flags?destination=" . uri_string(current_url()), '<span class="glyphicon glyphicon-ok-circle"></span>', 'title="Dismiss flags" class="btn btn-default btn-xs" role="button"'); ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7">No comments have been submitted for this narrative.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
    </div>
  </div>
</div>
