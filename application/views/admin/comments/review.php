<div class="container fixed-margin">
  <div class="page-header">
    <h1>Comment <?php echo $comment['comment_id']; ?> <small>Review</small></h1>
  </div>

  <div class="comment">
    <p><?php echo $comment['body']; ?></p>
  </div>

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
        <th><a href="#" class="sort-btn">Date</a></th>
        <th><a href="#" class="sort-btn">Reason</a></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($flags as $flag): ?>
        <tr>
          <td><?php print $flag['cflag_id']; ?></td>
          <td><?php print $flag['date_created']; ?></td>
          <td><?php print $flag['description']; ?></td>
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
