<div class="container fixed-margin">
  <div class="page-header">
    <h1>Narratives <small>View All</small></h1>
  </div>

  <div class="dropdown dropdown-margin float-left">
    <button class="btn dropdown-toggle " type="button" id="dropdownMenu1" data-toggle="dropdown">
      Sort By ... <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">ID</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Length</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Language</a></li>
    </ul>
  </div>

  <div class="dropdown dropdown-margin left-margin float-left">
    <button class="btn dropdown-toggle " type="button" id="dropdownMenu1" data-toggle="dropdown">
      Filter By ... <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Flagged</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Published</a></li>
      <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Unpublished</a></li>
    </ul>
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
        <th>ID</th>
        <th>Length</th>
        <th>Language</th>
        <th>Created</th>
        <th>Uploaded</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($narratives as $narrative): ?>
        <tr>
          <td><?php print $narrative['narrative_id']; ?></td>
          <td><?php printf('%d', $narrative['audio_length']/60); ?>:<?php printf('%02d', $narrative['audio_length']%60); ?></td>
          <td><?php print $narrative['language']; ?></td>
          <td><?php print $narrative['created']; ?></td>
          <td><?php print $narrative['uploaded']; ?></td>
          <td>
            <a href="#" title="View Comments" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-comment"></a>
            <a href="#" title="Edit" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-pencil"></a>
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
