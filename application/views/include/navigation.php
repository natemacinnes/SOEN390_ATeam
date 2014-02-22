<nav class="navbar navbar-default navbar-fixed-top fixed-margin" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="container">

		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php echo anchor('admin', 'Admin Panel', 'class="navbar-brand"'); ?>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Narratives <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><?php echo anchor('admin/narratives', 'View All'); ?></li>
						<li><?php echo anchor('admin/upload', 'Upload'); ?></li>
					</ul>
				</li>
				<li><?php echo anchor('admin/comments', 'Comments'); ?></li>
				<li><a href="#">Flags</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
					<li><?php echo anchor('home', 'User Homepage'); ?></li>
					<?php if ($logged_in_user): ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $logged_in_user['login'] ?><b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><?php echo anchor('#', 'Profile'); ?></li>
							<li><?php echo anchor('admin/logout', 'Log Out'); ?></li>
						</ul>
					</li>
				<?php else: ?>
					<li><?php echo anchor('admin/login', 'Log In'); ?></li>
				<?php endif; ?>
			</ul>

		</div><!-- /.navbar-collapse -->
	</div>
</nav>
<!-- used just to take up space given position fixed navbar -->
<div style="margin-top: 70px"></div>
