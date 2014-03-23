
<div class="home">
<?php
	if(!isset($narrative))
	{
		$narrative = -1;
	}
	echo form_hidden('toPlay', $narrative);
?>
	<div class="container">
    <div class="page-header">

      <div class="topic-header">
        <h4><?php echo $topic; ?></h4>
      </div>

      <h1><span id="page-header-you" class="lightblue">You</span><span id="page-header-deliberate">Deliberate</span></h1>

    </div>

		<div class="controls-container btn-toolbar">
			<div class="btn-group btn-group-horizontal sort-container">
				<a href="#" title="Recent narratives / Nouvelles discussions" class="btn btn-default disabled" role="button" data-toggle="tooltip" data-placement="top" data-container="body">Filter by / filtr&eacute; par: </a>
				<a href="#age" title="Most recent / Plus r&eacute;cent" class="btn btn-default" role="button" data-toggle="tooltip" data-placement="top" data-container="body"><span class="glyphicon glyphicon-time"></span></a>
				<a href="#agrees" title="Most agreed / Plus d'accords" class="btn btn-default" role="button" data-toggle="tooltip" data-placement="top" data-container="body"><span class="glyphicon glyphicon-thumbs-up" style="color: green;"></span></a>
				<a href="#disagrees" title="Most disagreed / Plus de d&eacute;saccords" class="btn btn-default" role="button" data-toggle="tooltip" data-placement="top" data-container="body"><span class="glyphicon glyphicon-thumbs-down" style="color: red;"></span></a>
			</div>

			<div class="btn-group btn-group-horizontal language-container">
				<a href="#en" title="English / Anglais" class="btn btn-default" role="button" data-toggle="tooltip" data-placement="top" data-container="body"><span class="flagicon flagicon-en"></span> EN</a>
				<a href="#fr" title="French / Fran&ccedil;ais" class="btn btn-default" role="button" data-toggle="tooltip" data-placement="top" data-container="body"><span class="flagicon flagicon-fr"></span> FR</a>
			</div>
			<div class="btn-group btn-group-horizontal filter-container">
				<a href="#history" title="Watched / Discussions vue" class="btn btn-default" role="button" data-toggle="tooltip" data-placement="top" data-container="body"><span class="glyphicon glyphicon-map-marker"></span></a>
			</div>

			<div class="help-container">
				<!--<a href=blah class="btn btn-default colorbox"><a href="www.youtube.com" title="Site tutorial&#013;Tutoriel du site" class="btn btn-info btn-lg btn-help colorbox"><span class="glyphicon glyphicon-question-sign"></span></a>
				-->
				<a href="tutorial/english" title="Site tutorial&#013;Tutoriel du site" class="btn btn-info btn-lg btn-help colorbox"><span class="glyphicon glyphicon-question-sign"></span></a>
				<a href="#" title="Give us your feedback&#013;Donnez-nous votre feedback" class="btn btn-warning btn-lg btn-feedback"><span class="glyphicon glyphicon-bullhorn"></span></a>
			</div>
		</div>

		<!-- this sets the width available & corners -->
		<div class="panel panel-default top-margin" id="narrative-wrapper">
			<div class="panel-heading">Click a bubble to play a narrative / ...</div>
		<!-- this sets the background color (must match recent-container) -->
			<div id="homepage-content-wrapper" class="clearfix panel-body">
				<div id="bubble-container">
					<div class="bubble-legend">
						<span class="bold">Legend / Legende:</span>
	          <div class="legend-circle purple display-inline-block left-margin"></div> For / Pour &nbsp;
	          <div class="legend-circle lightgrey display-inline-block left-margin"></div> Neutral / Neutre &nbsp;
	          <div class="legend-circle lightblue display-inline-block left-margin"></div> Against / Contre &nbsp;
          </div>

					<div class="svg-container svg-container-1 float-left"></p></div>

					<div class="svg-container svg-container-0 float-left"></div>

					<div class="svg-container svg-container-2 float-left"><p>Bob</p></div>
				</div>
			</div>
		</div>

    <!-- this wraps the history bubbles -->
		<div class="panel panel-default float-right" id="recent-wrapper">
			<div class="panel-heading">History / Deja Vu</div>
			<div class="panel-body">
				<div id="recent-container">
					<div class="svg-container svg-container-history"></div>
				</div>
			</div>
		</div>
		<div class="clear"></div>

	</div>
</div>
