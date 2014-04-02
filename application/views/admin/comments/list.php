<div class="container fixed-margin">
  <div class="page-header">
    <h1>Comments <small>View All</small></h1>
  </div>

  <!--<div class="dropdown dropdown-margin2 float-left">
    <button class="btn dropdown-toggle " type="button" id="dropdownMenu1" data-toggle="dropdown">
      Filter By ... <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Published</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Unpublished</a></li>
    </ul>
  </div>-->

  <!--THIS GENERATES PAGINATION-->
  <?php
    if (strlen($links)){
      echo $links;
    }
  ?>
  <div class="clear"></div>

  <table class="table table-hover">
    <thead>
      <tr>
        <th><a href="<?php echo site_url("admin/comments/narrative/" . ($sort_order == "desc" && $sort_by == "narrative" ? "asc" : "desc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "narrative") ? " active $sort_order" : ""; ?>">Narrative ID</a></th>
        <th><a href="<?php echo site_url("admin/comments/id/" . ($sort_order == "desc" && $sort_by == "id" ? "asc" : "desc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "id") ? " active $sort_order" : ""; ?>">Comment ID</a></th>
        <th><a href="<?php echo site_url("admin/comments/parent/" . ($sort_order == "desc" && $sort_by == "parent" ? "asc" : "desc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "parent") ? " active $sort_order" : ""; ?>">Parent ID</a></th>
        <th>Content</th>
        <th><a href="<?php echo site_url("admin/comments/created/" . ($sort_order == "desc" && $sort_by == "created" ? "asc" : "desc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "created") ? " active $sort_order" : ""; ?>">Created</a></th>
        <th><a href="<?php echo site_url("admin/comments/flags/" . ($sort_order == "desc" && $sort_by == "flags" ? "asc" : "desc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "flags") ? " active $sort_order" : ""; ?>">Flags</a></th>
        <th><a href="<?php echo site_url("admin/comments/status/" . ($sort_order == "desc" && $sort_by == "status" ? "asc" : "desc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "status") ? " active $sort_order" : ""; ?>">Status</a></th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($comments as $comment): ?>
        <tr>
          <td><?php print $comment['narrative_id']; ?></td>
          <td><?php print $comment['comment_id']; ?></td>
          <td><?php if ($comment['parent_comment']) { echo anchor('admin/comments/' . $comment['parent_comment'] . '/review', $comment['parent_comment']); } ?></td>
          <td><?php print xss_clean($comment['body']); ?></td>
          <td><?php print $comment['created']; ?></td>
          <td><?php echo anchor('admin/comments/' . $comment['comment_id'] . '/review', $comment['flags']); ?></td>
          <td>Published</td>
          <td>
            <?php echo anchor("admin/comments/" . $comment['comment_id'] . "/delete", '<span class="glyphicon glyphicon-remove"></span>', 'title="Delete" class="btn btn-default btn-xs" role="button"'); ?>
            <?php if ($comment['flags']): ?>
              <?php echo anchor("admin/comments/" . $comment['comment_id'] . "/dismiss_flags", '<span class="glyphicon glyphicon-ok-circle"></span>', 'title="Dismiss flags" class="btn btn-default btn-xs" role="button"'); ?>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!--THIS GENERATES PAGINATION-->
  <?php
    if (strlen($links)){
      echo $links;
    }
  ?>
  <div class="clear"></div>

</div>
