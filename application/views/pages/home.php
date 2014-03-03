<div class="home">
  <div class="container">
    <div class="page-header">
      <h1><span id="page-header-you">You</span><span id="page-header-deliberate">Deliberate</span></h1>
	  <h4>Topic: <?php echo $topic; ?></h4>
      <h4>Should GMO foods be labeled? / Est-ce que les aliments GMO devraient être étiquetés comme tels?</h4>
    </div>


    <!--<div class="sort-container float-left">
      <a href="#" title="New narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-certificate"></span> New</a>
      <a href="#" title="Popular narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-fire"></span> Popular</a>
      <a href="#" title="Agreed narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-thumbs-up"></span> Agreed</a>
      <a href="#" title="Disagreed narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-thumbs-down"></span> Disagreed</a>
    </div>
    <div class="filter-container float-right">
      <a href="#" title="English" class="btn btn-default" role="button"><span class="glyphicon glyphicon-minus"></span> EN</a>
      <a href="#" title="French" class="btn btn-default" role="button"><span class="glyphicon glyphicon-minus"></span> FR</a>
    </div>
    <div class="clear"></div>-->

    <div class="controls-container btn-toolbar">
      <div class="btn-group btn-group-horizontal sort-container">
        <a href="#age" title="New narratives&#013;Nouvelles discussions" class="btn btn-default" role="button"><span class="glyphicon glyphicon-time"></span> Recent</a>
        <a href="#agrees" title="Agreed narratives&#013;Discussions approuv&eacute;es" class="btn btn-default active" role="button">
          <span class="glyphicon glyphicon-thumbs-up" style="color: green;"></span> /
          <span class="glyphicon glyphicon-thumbs-down" style="color: red;"></span>
          Popular
        </a>
      </div>
      <div class="btn-group btn-group-horizontal filter-container">
        <a href="en" title="English&#013;Anglais" class="btn btn-default" role="button"><span class="flagicon flagicon-en"></span> EN</a>
        <a href="fr" title="French&#013;Fran&ccedil;ais" class="btn btn-default" role="button"><span class="flagicon flagicon-fr"></span> FR</a>
      </div>
      <div class="help-container">
        <!--<a href=blah class="btn btn-default colorbox"><a href="www.youtube.com" title="Site tutorial&#013;Tutoriel du site" class="btn btn-info btn-lg btn-help colorbox"><span class="glyphicon glyphicon-question-sign"></span></a>
        -->
        <a href="tutorial/english" title="Site tutorial&#013;Tutoriel du site" class="btn btn-info btn-lg btn-help colorbox"><span class="glyphicon glyphicon-question-sign"></span></a>
        <a href="#" title="Give us your feedback&#013;Donnez-nous votre feedback" class="btn btn-warning btn-lg btn-feedback"><span class="glyphicon glyphicon-bullhorn"></span></a>
      </div>
    </div>

    <!-- this sets the width available & corners -->
    <div id="homepage-content-wrapper" class="clearfix">
    <!-- this sets the background color (must match recent-container) -->
      <div class="inner clearfix">
        <!-- right column that appears to expand to meet left column -->
        <div id="recent-container"></div>
        <!-- left column -->
        <div id="bubble-container" class="float-left">
          <div class="svg-container svg-container-1 float-left"></div>
          <div class="svg-container svg-container-0 float-left"></div>
          <div class="svg-container svg-container-2 float-left"></div>
        </div>
      </div>
    </div>

    <div class="clear"></div>
  </div>
</div>
