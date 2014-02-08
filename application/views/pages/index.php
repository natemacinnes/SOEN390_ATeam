<div class="home">
  <div class="container">
    <div class="page-header">
      <h1><span id="page-header-you">You</span><span id="page-header-deliberate">Deliberate</span></h1>
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

    <div id="bubble-container" class="top-margin float-left">
      <div class="sort-container">
        <div class="btn-group btn-group-vertical">
          <a href="#views" title="Number of listens&#013;Nombres d'&eacute;coutes" class="btn btn-default active" role="button"><span class="glyphicon glyphicon-headphones"></span></a>
          <a href="#age" title="New narratives&#013;Nouvelles discussions" class="btn btn-default" role="button"><span class="glyphicon glyphicon-time"></span></a>
          <a href="#popular" title="Popular narratives&#013;Discussions populaires" class="btn btn-default" role="button"><span class="glyphicon glyphicon-star"></span></a>
          <a href="#agrees" title="Agreed narratives&#013;Discussions approuv&eacute;es" class="btn btn-default" role="button"><span class="glyphicon glyphicon-thumbs-up" style="color: green;"></span></a>
          <a href="#disagrees" title="Disagreed narratives&#013;Discussions contest&eacute;es" class="btn btn-default" role="button"><span class="glyphicon glyphicon-thumbs-down" style="color: red;"></span></a>
        </div>
      </div>
      <center class="svg-container"></center>
      <div class="filter-container">
        <div class="btn-group btn-group-vertical">
          <a href="en" title="English&#013;Anglais" class="btn btn-default" role="button"><span class="flagicon flagicon-en"></span></a>
          <a href="fr" title="French&#013;Fran&ccedil;ais" class="btn btn-default" role="button"><span class="flagicon flagicon-fr"></span></a>
        </div>
      </div>
      <div class="help-container">
        <a href="#" title="Site tutorial&#013;Tutoriel du site" class="btn btn-info btn-lg btn-help"><span class="glyphicon glyphicon-question-sign"></span></a>
        <a href="#" title="Give us your feedback&#013;Donnez-nous votre feedback" class="btn btn-warning btn-lg btn-feedback"><span class="glyphicon glyphicon-bullhorn"></span></a>
      </div>
    </div>


    <div id="recent-container" class="top-margin float-left">
    </div>

    <div class="clear"></div>

    <div class="debug">
      Bubble ring mode:
      <div class="debug-rings">
        <input id="debug-ring-toggle-hover" type="radio" name="ring-toggle" value="0" checked="checked" />
        <label for="debug-ring-toggle-hover">Hover</label>
        <input id="debug-ring-toggle-transparent" type="radio" name="ring-toggle" value="1" />
        <label for="debug-ring-toggle-transparent">Transparent</label>
        <input id="debug-ring-toggle-always" type="radio" name="ring-toggle" value="2" />
        <label for="debug-ring-toggle-always">Always</label>
      </div>
      <div class="debug-opacity">
        <label for="debug-ring-toggle-opacity">Opacity</label>
        <input id="debug-ring-toggle-opacity" type="text" size="1" name="ring-opacity" value="0.3" />
      </div>

      Text display mode:
      <div class="debug-text">
        <input id="debug-text-toggle-none" type="radio" name="text-toggle" value="0" />
        <label for="debug-text-toggle-none">None</label>
        <input id="debug-text-toggle-hover" type="radio" name="text-toggle" value="1" checked="checked" />
        <label for="debug-text-toggle-hover">Hover</label>
        <input id="debug-text-toggle-always" type="radio" name="text-toggle" value="2" />
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
        <input id="debug-text-content-toggle-glyph-play" type="radio" name="text-content-toggle" value="4" checked="checked" />
        <label for="debug-text-content-toggle-glyph-play">Glyph+play on hover</label>
        <input id="debug-text-content-toggle-glyph-play-animate" type="radio" name="text-content-toggle" value="3" />
        <label for="debug-text-content-toggle-glyph-play-animate">Glyph, play animation on hover</label>
      </div>

      Bubble color fill mode:
      <div class="debug-color">
        <input id="debug-color-toggle-grey" type="radio" name="color-toggle" value="0" />
        <label for="debug-color-toggle-grey">Grey</label>
        <input id="debug-color-toggle-greys" type="radio" name="color-toggle" value="1" />
        <label for="debug-color-toggle-greys">Greys</label>
        <input id="debug-color-toggle-color" type="radio" name="color-toggle" value="2" />
        <label for="debug-color-toggle-color">Color (R/G)</label>
        <input id="debug-color-toggle-color2" type="radio" name="color-toggle" value="3" />
        <label for="debug-color-toggle-color2">Color (B/P)</label>
        <input id="debug-color-toggle-hue" type="radio" name="color-toggle" value="4" checked="checked" />
        <label for="debug-color-toggle-hue">Hue</label>
      </div>
    </div>

  </div>

</div>
