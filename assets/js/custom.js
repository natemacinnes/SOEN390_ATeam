jQuery(document).ready(function() {
  loadBubbles('narrative_id');

  // Toggle buttons for navigation links
  jQuery('.btn-group a').click(function() {
    jQuery('.btn-group a').removeClass('active');
    jQuery(this).toggleClass('active');
    var sortby = jQuery(this).attr('href').substring(1);
    loadBubbles(sortby);
  });
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

  var diameter = (document.getElementById("bubble-container").offsetWidth)/2
  format = d3.format(",d"),
  color = d3.scale.category20c();

  var pack = d3.layout.pack()
    .sort(null)
    .size([diameter, diameter])
    .value(function(d) { return d[sortBy]; })
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
      .style("fill", function(d) { console.log(d); return !d.children ? color(d.parent.name) : "#eeeeee"; })

    var nodes = vis.append("text")
      .attr("dx", function(d) { return d.x; })
      .attr("dy", function(d) { return d.y; })
      .style("text-anchor", "middle")
      .text(function(d) { return d[sortBy]; });

    jQuery('svg.bubble .node').hover(bubbleMouseIn, bubbleMouseOut);

    updateVis('views');

    function updateVis(sortBy) {
      console.log('updating bubbles to be sorted by ' + sortBy);

      pack.value(function(d) { return d[sortBy]; });
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

      nodes.text(function(d) { return d[sortBy]; });

      nodes.transition()
        .duration(3000)
        .attr("dx", function(d) { return d.x; })
        .attr("dy", function(d) { return d.y; });

    }

    d3.select(self.frameElement).style("height", diameter + "px");

  });
}
