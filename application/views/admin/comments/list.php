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
        <th><a href="<?php echo site_url("admin/comments/narrative/" . ($sort_order == "asc" && $sort_by == "narrative" ? "desc" : "asc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "narrative") ? " active $sort_order" : ""; ?>">Narrative #</a></th>
        <th><a href="<?php echo site_url("admin/comments/id/" . ($sort_order == "asc" && $sort_by == "id" ? "desc" : "asc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "id") ? " active $sort_order" : ""; ?>">Comment #</a></th>
        <th><a href="<?php echo site_url("admin/comments/parent/" . ($sort_order == "asc" && $sort_by == "parent" ? "desc" : "asc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "parent") ? " active $sort_order" : ""; ?>">Reply to #</a></th>
        <th>Content</th>
        <th><a href="<?php echo site_url("admin/comments/created/" . ($sort_order == "asc" && $sort_by == "created" ? "desc" : "asc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "created") ? " active $sort_order" : ""; ?>">Created</a></th>
        <th><a href="<?php echo site_url("admin/comments/flags/" . ($sort_order == "asc" && $sort_by == "flags" ? "desc" : "asc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "flags") ? " active $sort_order" : ""; ?>">Flags</a></th>
        <th><a href="<?php echo site_url("admin/comments/status/" . ($sort_order == "asc" && $sort_by == "status" ? "desc" : "asc") . "/$offset"); ?>" class="sort-btn<?php echo ($sort_by == "status") ? " active $sort_order" : ""; ?>">Status</a></th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($comments as $comment): ?>
        <tr>
          <td><?php print $comment['narrative_id']; ?></td>
          <td><?php print $comment['comment_id']; ?></td>
          <td><?php if ($comment['parent_comment']) { echo anchor('admin/comments/' . $comment['parent_comment'] . '/review', $comment['parent_comment']); } ?></td>
          <td><?php print $comment['body']; ?></td>
          <td><?php print $comment['created']; ?></td>
          <td><?php echo anchor('admin/comments/' . $comment['comment_id'] . '/review', $comment['flags']); ?></td>
          <td>Published</td>
          <td>
            <?php echo anchor("admin/comments/" . $comment['comment_id'] . "/delete", '<span class="glyphicon glyphicon-remove"></span>', 'title="Delete" class="btn btn-default btn-xs" role="button"'); ?>
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
