jQuery(document).ready(function() {
  loadBubbles();

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

// Returns a flattened hierarchy containing all leaf nodes under the root.
function classes(root) {
  var classes = [];

  function recurse(name, node) {
  if (node.children) node.children.forEach(function(child) { recurse(node.name, child); });
  else classes.push({packageName: name, className: node.name, value: node.size});
  }

  recurse(null, root);
  return {children: classes};
}

function loadBubbles(sortBy) {
  var diameter = (document.getElementById("bubble-container").offsetWidth)/2
    format = d3.format(",d"),
    color = d3.scale.category20c();

  var bubble = d3.layout.pack()
    .sort(null)
    .size([diameter, diameter])
    .padding(1.5);

  var svg = d3.select(".test").html('').append("svg")
    .attr("width", diameter)
    .attr("height", diameter)
    .attr("class", "bubble");


  // sortBy may be undefined. If so, don't call ajax/bubbles/undefined -_-
  var url = yd_settings.site_url + "ajax/bubbles";
  if (typeof(sortBy) !== 'undefined') {
    url += '/' + sortBy;
  }

  // Create the SVG bubble structure
  d3.json(url, function(error, root) {
    var node = svg.selectAll(".node")
      .data(bubble.nodes(classes(root))
      .filter(function(d) { return !d.children; }))
    .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

    node.append("title")
      .text(function(d) { return d.className });

    node.append("circle")
      .attr("r", function(d) { return d.r; })
      .style("fill", function(d) { return color(d.packageName); });

    node.append("text")
      .attr("dy", ".3em")
      .style("text-anchor", "middle")
      .text(function(d) { return d.className; });

    d3.select(self.frameElement).style("height", diameter + "px");
    jQuery('svg.bubble .node').hover(bubbleMouseIn, bubbleMouseOut);
  });
}
