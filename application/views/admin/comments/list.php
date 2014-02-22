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

  <ul class="pagination float-right">
    <li><a href="#">&laquo;</a></li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#">&raquo;</a></li>
  </ul>
  <div class="clear"></div>

  <table class="table table-hover">
    <thead>
      <tr>
        <th><a href="#" class="sort-btn active desc">ID</a></th>
        <th><a href="#" class="sort-btn">Parent ID</a></th>
        <th><a href="#" class="sort-btn">Content</a></th>
        <th><a href="#" class="sort-btn">Created</a></th>
        <th><a href="#" class="sort-btn">Flags</a></th>
        <th><a href="#" class="sort-btn">Status</a></th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($comments as $comment): ?>
        <tr>
          <td><?php print $comment['comment_id']; ?></td>
          <td><?php print $comment['parent_comment']; ?></td>
          <td><?php print $comment['body']; ?></td>
          <td><?php print $comment['created']; ?></td>
          <td><?php echo anchor('admin/comment/' . $comment['comment_id'] . '/review', 0/*$comment['flags']*/); ?></td>
          <td>Published</td>
          <td>
            <a href="#" title="Delete" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <ul class="pagination float-right">
    <li><a href="#">&laquo;</a></li>
    <li><a href="#">1</a></li>
    <li><a href="#">2</a></li>
    <li><a href="#">3</a></li>
    <li><a href="#">4</a></li>
    <li><a href="#">5</a></li>
    <li><a href="#">&raquo;</a></li>
  </ul>
  <div class="clear"></div>

</div>
