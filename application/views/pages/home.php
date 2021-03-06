
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
        <h4><?php echo xss_clean($topic); ?></h4>
      </div>

      <h2><span id="page-header-you" class="lightblue">You</span><span id="page-header-deliberate">Deliberate</span> / <span id="page-header-you" class="lightblue">Vous</span><span id="page-header-deliberate">Deliberez</span></h2>

    </div>

		<div class="controls-container btn-toolbar">
			<div class="btn-group btn-group-horizontal sort-container">
				<a href="#" class="btn btn-default disabled" role="button">Filter by / filtr&eacute; par: </a>
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
				<a href="tutorial/english" title="Site tutorial / Tutoriel du site" class="btn btn-info btn-lg btn-help colorbox" data-toggle="tooltip" data-placement="top" data-container="body"><span class="glyphicon glyphicon-question-sign"></span></a>
			</div>
		</div>

		<!-- this sets the width available & corners -->
		<div class="panel panel-default top-margin" id="narrative-wrapper">
			<div class="panel-heading">Click a bubble to listen to an opinion / Appuyez sur une bulle pour &eacute;couter une opinion </div>
			<!-- this sets the background color (must match recent-container) -->
			<div id="homepage-content-wrapper" class="clearfix panel-body">
				<div id="bubble-container">
					<div class="bubble-legend">
						<span class="bold">Legend / Legende:</span>
	          <div class="legend-circle purple display-inline-block left-margin"></div>
	          <span class="for-bubbles"> For / Pour &nbsp; </span>
	          <div class="legend-circle lightgrey display-inline-block left-margin"></div>
	         	<span class="neutral-bubbles"> Ambivalent / Ambivalente &nbsp; </span>
	          <div class="legend-circle lightblue display-inline-block left-margin"></div>
	          <span class="agains-bubbles"> Against / Contre &nbsp; </span>
          </div>

					<div class="svg-container svg-container-1 float-left"></p></div>

					<div class="svg-container svg-container-0 float-left"></div>

					<div class="svg-container svg-container-2 float-left"></div>
				</div>
			</div>
		</div>

		<div class="panel panel-default float-right" id="recent-wrapper">
			<div class="panel-heading">History / Historique</div>
			<div class="panel-body">
				<div id="recent-container">
					<div class="svg-container svg-container-history"></div>
				</div>
			</div>
		</div>

		<div class="dont-contact-us">
      <a id="mail-to" href="mailto:<?php echo xss_clean($contact); ?>?Subject=YouDeliberate" title="Send us an e-mail &#013; Envoyez un e-mail..." target="_top" data-toggle="tooltip" data-placement="bottom" data-container="body"> Contact us <span class="glyphicon glyphicon-envelope"></span> Contactez nous </a>
    </div>

		<div class="clear"></div>

	</div>

</div>
