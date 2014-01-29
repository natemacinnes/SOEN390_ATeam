<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="container">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">Admin Panel</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Narratives <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><?php echo anchor('viewnarratives', 'View All'); ?></li>
            <li><?php echo anchor('admin/upload', 'Upload'); ?></li>
          </ul>
        </li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div>
</nav>

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
        <th>Manage</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>5:20</td>
        <td>EN</td>
        <td><a href="#" class="btn btn-default btn-xs" role="button">Edit</a></td>
        <td><a href="#" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a></td>
      </tr>
      <tr> 
        <td>2</td>
        <td>3:23</td>
        <td>EN</td>
        <td><a href="#" class="btn btn-default btn-xs" role="button">Edit</a></td>
        <td><a href="#" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a></td>
      </tr>
      <tr> 
        <td>3</td>
        <td>2:53</td>
        <td>FR</td>
        <td><a href="#" class="btn btn-default btn-xs" role="button">Edit</a></td>
        <td><a href="#" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a></td>
      </tr>
      <tr> 
        <td>4</td>
        <td>4:12</td>
        <td>EN</td>
        <td><a href="#" class="btn btn-default btn-xs" role="button">Edit</a></td>
        <td><a href="#" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a></td>
      </tr>
      <tr> 
        <td>5</td>
        <td>5:37</td>
        <td>FR</td>
        <td><a href="#" class="btn btn-default btn-xs" role="button">Edit</a></td>
        <td><a href="#" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a></td>
      </tr>
      <tr> 
        <td>6</td>
        <td>3:45</td>
        <td>EN</td>
        <td><a href="#" class="btn btn-default btn-xs" role="button">Edit</a></td>
        <td><a href="#" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a></td>
      </tr>
      <tr> 
        <td>7</td>
        <td>4:52</td>
        <td>EN</td>
        <td><a href="#" class="btn btn-default btn-xs" role="button">Edit</a></td>
        <td><a href="#" class="btn btn-default btn-xs" role="button"><span class="glyphicon glyphicon-remove"></a></td>
      </tr>
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