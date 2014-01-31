jQuery(document).ready(function() {
  loadBubbles('views');
});

function loadBubbles(sortBy) {

  // sortBy may be undefined. If so, don't call ajax/bubbles/undefined -_-
  var url = yd_settings.site_url + "ajax/bubbles";
  if (typeof(sortBy) !== 'undefined') {
    url += '/' + sortBy;
  }

  if (!jQuery('#bubble-container').not('.bubbles-processed').addClass('bubbles-processed').length) {
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
      .text(function(d) { return (d.children ? d.name : 'Narrative ' + d['narrative_id'] + ": " + format(d.value)); });

    var circles = vis.append("circle")
      .attr("cx", function(d) { return d.x; })
      .attr("cy", function(d) { return d.y; })
      .attr("r", function(d) { return d.r; })
      .attr("id", function(d) { return 'narrative-' + d['narrative_id']; })
      .attr("class", function(d) { return !d.children ? 'node-base' : 'node-parent'; })
      .style("fill", function(d) { return !d.children ? color(d.parent.name) : "#eeeeee"; })

    var nodes = vis.append("text")
      .attr("dx", function(d) { return d.x; })
      .attr("dy", function(d) { return d.y; })
      .style("text-anchor", "middle")
      .text(bubbles_label_text[sortBy]);

    console.log("checking for a node");

    $(".node-base").click(function() {
      jQuery.colorbox({href: yd_settings.site_url + "narratives/" + this.__data__.narrative_id});
      loadMediaElement();
    });

    updateVis('views');

    function updateVis(sortBy) {
      console.log('updating bubbles to be sorted by ' + sortBy);

      pack.value(bubbles_sorting[sortBy]);
      var data1 = pack.nodes(data);
      titles.attr("x", function(d) { return d.x; })
        .attr("y", function(d) { return d.y; })
        .text(function(d) { return (d.children ? d.name : 'Narrative ' + d['narrative_id'] + ": " + format(d.value)); });

      circles.transition()
          .duration(700)
          .attr("cx", function(d) { return d.x; })
          .attr("cy", function(d) { return d.y; })
          .attr("r", function(d) { return d.r; });

      nodes.text(bubbles_label_text[sortBy]);

      nodes.transition()
        .duration(700)
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

function dateFromString(str) {
  var a = $.map(str.split(/[^0-9]/), function(s) { return parseInt(s, 10) });
  return new Date(a[0], a[1]-1 || 0, a[2] || 1, a[3] || 0, a[4] || 0, a[5] || 0, a[6] || 0);
}

// +1 because 0 views/agrees/disagrees is valid state, but results in weird bubble rendering
// TODO: normalization
bubbles_sorting = {
  'agrees': function(d) { return d['agrees']+1; },
  'disagrees': function(d) { return d['disagrees']+1; },
  'views': function(d) { return parseInt(d['views']+1) + 150/30; },
  'age': function(d) { var dcreated = dateFromString(d['created']); return dcreated.getYear() + dcreated.getMonth()/12*900 + dcreated.getDay()/31*100; },
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
  if (jQuery('audio,video').not('player-processed').addClass('player-processed').length) {
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
      var narrative_id = jQuery('.player-wrapper').attr('id').substring(10);
      xmlhttp.open("GET",yd_settings.site_url + "ajax/audioImage/" + narrative_id + "/" + myaudio.currentTime, true);
      myaudio.play();
      xmlhttp.send();
    }, false);
  }
}
