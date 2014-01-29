jQuery(document).ready(function() {
  loadBubbles('views');
  loadMediaElement();
});

function bubbleMouseIn(bubble) {
  console.log('Mouse in: ' + this);
}

function bubbleMouseOut(bubble) {
  console.log('Mouse out: ' + this);
}

function loadBubbles(sortBy) {
  // sortBy may be undefined. If so, don't call ajax/bubbles/undefined -_-
  var url = yd_settings.site_url + "ajax/bubbles";
  if (typeof(sortBy) !== 'undefined') {
    url += '/' + sortBy;
  }

  if (!jQuery('.bubble-container').length) {
    return false;
  }
  var diameter = (document.getElementById("bubble-container").offsetWidth)/2
  format = d3.format(",d"),
  color = d3.scale.category20c();

  var pack = d3.layout.pack()
    .sort(null)
    .size([diameter, diameter])
    .value(bubbles_sorting[sortBy])
    .padding(1.5);

  // Create the SVG bubble structure
  var svg = d3.select(".test").append("svg")
    .attr("width", diameter)
    .attr("height", diameter)
    .attr("class", "bubble");

  d3.json(url, function(error, data) {
    console.log('creating bubbles sorted by ' + sortBy);

    var vis = svg.datum(data).selectAll('.node')
      .data(pack.nodes)
      .enter()
        .append('g');

    var titles = vis.append('title')
      .attr("x", function(d) { return d.x; })
      .attr("y", function(d) { return d.y; })
      .text(function(d) { return d.narrative_id +
        (d.children ? "" : ": " + format(d.value)); });

    var circles = vis.append("circle")
      .attr("cx", function(d) { return d.x; })
      .attr("cy", function(d) { return d.y; })
      .attr("r", function(d) { return d.r; })
      .attr("id", function(d) { return 'narrative-' + d.narrative_id; })
      .attr("class", function(d) { return !d.children ? 'node-base' : 'node-parent'; })
      .style("fill", function(d) { console.log(d); return !d.children ? color(d.parent.name) : "#eeeeee"; })

    var nodes = vis.append("text")
      .attr("dx", function(d) { return d.x; })
      .attr("dy", function(d) { return d.y; })
      .style("text-anchor", "middle")
      .text(bubbles_label_text[sortBy]);

    jQuery('svg.bubble .node').hover(bubbleMouseIn, bubbleMouseOut);

    updateVis('views');

    function updateVis(sortBy) {
      console.log('updating bubbles to be sorted by ' + sortBy);

      pack.value(bubbles_sorting[sortBy]);
      var data1 = pack.nodes(data);
      titles.attr("x", function(d) { return d.x; })
        .attr("y", function(d) { return d.y; })
        .text(function(d) { return d.name +
            (d.children ? "" : ": " + format(d.value)); });

      circles.transition()
          .duration(3000)
          .attr("cx", function(d) { return d.x; })
          .attr("cy", function(d) { return d.y; })
          .attr("r", function(d) { return d.r; });

      nodes.text(bubbles_label_text[sortBy]);

      nodes.transition()
        .duration(3000)
        .attr("dx", function(d) { return d.x; })
        .attr("dy", function(d) { return d.y; });
    }

    // Toggle buttons for navigation links
    jQuery('.btn-group a').click(function() {
      jQuery('.btn-group a').removeClass('active');
      jQuery(this).toggleClass('active');
      var sortBy = jQuery(this).attr('href').substring(1);
      updateVis(sortBy);
    });

    d3.select(self.frameElement).style("height", diameter + "px");

  });
}

bubbles_sorting = {
  'agrees': function(d) { return d['agrees']; },
  'disagrees': function(d) { return d['disagrees']; },
  'views': function(d) { return parseInt(d['views']) + 150/30; },
  'age': function(d) { var dcreated = new Date(d['created']); return dcreated.getYear() + dcreated.getMonth()/12*900 + dcreated.getDay()/31*100; },
  // TODO
  'popular': function(d) { return d['narrative_id']; }
}

bubbles_label_text = {
  'agrees': function(d) { return d['agrees']; },
  'disagrees': function(d) { return d['disagrees']; },
  'views': function(d) { return d['views']; },
  'age': function(d) { return !d.children ? String(d['created']).split(' ')[0] : null; },
  // TODO
  'popular': function(d) { return d['narrative_id']; }
}

function loadMediaElement() {
  jQuery('audio,video').mediaelementplayer({
    // the order of controls you want on the control bar (and other plugins below)
    features: ['playpause','current','progress','duration','tracks','volume'],
    // show framecount in timecode (##:00:00:00)
    showTimecodeFrameCount: true
   });

  //AJAX function that changes the picture according to the time of the
  //the audio.
  myaudio=document.getElementById("narrative_audio");
  myaudio.addEventListener("timeupdate", function(e) {
    //document.getElementById('current-time').innerHTML = myaudio.currentTime;
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
      if (xmlhttp.readyState==4 && xmlhttp.status==200) {
        document.getElementById("audioImage").src = xmlhttp.responseText;
      }
    }
    // TODO make this jQuery using jQuery.get()
    // TODO make this a real controller method
    var narrative_id = jQuery('.player-wrapper').attr('id').substring(10)
    xmlhttp.open("GET",yd_settings.site_url + "ajax/audioImage/" + narrative_id + "/" + myaudio.currentTime, true);
    xmlhttp.send();
  }, false);
}
