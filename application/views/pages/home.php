<div class="home">
  <div class="container">
    <div class="page-header">
      <h1><span id="page-header-you">You</span><span id="page-header-deliberate">Deliberate</span></h1>
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

    <div class="panel-group" id="accordion">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#debugInfo">
              Debugging - configuration management
            </a>
          </h4>
        </div>
        <div id="debugInfo" class="panel-collapse collapse">
          <div class="debug panel-body">
            Bubble cluster/positioning mode:
            <div class="debug-position">
              <input id="debug-position-toggle-single" type="radio" name="position-toggle" value="0" />
              <label for="debug-position-toggle-single">Single</label>
              <input id="debug-position-toggle-multi" type="radio" name="position-toggle" value="1" checked="checked" />
              <label for="debug-position-toggle-multi">Multi</label>
            </div>

            Recent sorting method
            <div class="debug-recent-sort">
              <input id="debug-recent-sort-toggle-sort" type="radio" name="recent-sort-toggle" value="0" />
              <label for="debug-recent-sort-toggle-sort">Sort</label>
              <input id="debug-recent-sort-toggle-filter" type="radio" name="recent-sort-toggle" value="1" checked="checked" />
              <label for="debug-recent-sort-toggle-filter">Filter</label>
            </div>

            Bubble ring mode:
            <div class="debug-rings">
              <input id="debug-ring-toggle-hover" type="radio" name="ring-toggle" value="0" />
              <label for="debug-ring-toggle-hover">Hover</label>
              <input id="debug-ring-toggle-transparent" type="radio" name="ring-toggle" value="1" />
              <label for="debug-ring-toggle-transparent">Transparent</label>
              <input id="debug-ring-toggle-always" type="radio" name="ring-toggle" value="2" checked="checked" />
              <label for="debug-ring-toggle-always">Always</label>
              <input id="debug-ring-toggle-always-antihover" type="radio" name="ring-toggle" value="3" />
              <label for="debug-ring-toggle-always-antihover">Always+Anti-hover</label>
            </div>
            <div class="debug-ring-opacity">
              <label for="debug-ring-toggle-opacity">Opacity</label>
              <input id="debug-ring-toggle-opacity" type="text" size="1" name="ring-opacity" value="0.3" />
            </div>
            <div class="debug-ring-radius">
              <label for="debug-ring-toggle-radius">Inner Radius</label>
              <input id="debug-ring-toggle-radius" type="text" size="1" name="ring-radius" value="0.8" />
            </div>

            Text display mode:
            <div class="debug-text">
              <input id="debug-text-toggle-none" type="radio" name="text-toggle" value="0" />
              <label for="debug-text-toggle-none">None</label>
              <input id="debug-text-toggle-hover" type="radio" name="text-toggle" value="1" />
              <label for="debug-text-toggle-hover">Hover</label>
              <input id="debug-text-toggle-always" type="radio" name="text-toggle" value="2" checked="checked" />
              <label for="debug-text-toggle-always">Always</label>
            </div>

            Text content mode:
            <div class="debug-text-content">
              <input id="debug-text-content-toggle-metric" type="radio" name="text-content-toggle" value="0" />
              <label for="debug-text-content-toggle-metric">Metric</label>
              <input id="debug-text-content-toggle-glyph" type="radio" name="text-content-toggle" value="1" />
              <label for="debug-text-content-toggle-glyph">Glyph</label>
              <input id="debug-text-content-toggle-glyph-play" type="radio" name="text-content-toggle" value="2" />
              <label for="debug-text-content-toggle-glyph-play">Glyph, play on hover</label>
              <input id="debug-text-content-toggle-glyph-playhover" type="radio" name="text-content-toggle" value="4"  checked="checked" />
              <label for="debug-text-content-toggle-glyph-playhover">Play, glyph+play on hover</label>
              <input id="debug-text-content-toggle-glyph-play-animate" type="radio" name="text-content-toggle" value="3" />
              <label for="debug-text-content-toggle-glyph-play-animate">Play, glyph+play animation on hover</label>
            </div>

            Bubble color fill mode:
            <div class="debug-color">
              <input id="debug-color-toggle-grey" type="radio" name="color-toggle" value="0" />
              <label for="debug-color-toggle-grey">Grey</label>
              <input id="debug-color-toggle-greys" type="radio" name="color-toggle" value="1" />
              <label for="debug-color-toggle-greys">Greys</label>
              <input id="debug-color-toggle-color" type="radio" name="color-toggle" value="2" />
              <label for="debug-color-toggle-color">Color (R/G)</label>
              <input id="debug-color-toggle-color2" type="radio" name="color-toggle" value="3" checked="checked" />
              <label for="debug-color-toggle-color2">Color (B/P)</label>
              <input id="debug-color-toggle-hue" type="radio" name="color-toggle" value="4" />
              <label for="debug-color-toggle-hue">Hue</label>
            </div>
            <div class="debug-bubble-opacity">
              <label for="debug-bubble-toggle-opacity">Opacity</label>
              <input id="debug-bubble-toggle-opacity" type="text" size="1" name="ring-opacity" value="0.1" />
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

</div>
