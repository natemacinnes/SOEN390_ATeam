<div class="home">
	<div class="container">
			<h1>YouDeliberate</h1>
			
			<h3>Should GMO foods be labelled?</h3>
			
			
			<div class="sort-container float-left">
				<div class="btn-group">
					<a href="#" class="btn btn-default disabled" role="button">Sort by: </a>
					<a href="#" title="New narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-certificate"></span> New</a>
					<a href="#" title="Popular narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-fire"></span> Popular</a>
					<a href="#" title="Agreed narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-thumbs-up"></span> Agreed</a>
					<a href="#" title="Disagreed narratives" class="btn btn-default" role="button"><span class="glyphicon glyphicon-thumbs-down"></span> Disagreed</a>
				</div>
			</div>
			<div class="filter-container float-right">
				<div class="btn-group">
					<a href="#" class="btn btn-default disabled" role="button">Filter by: </a>
					<a href="#" title="English" class="btn btn-default" role="button"><span class="glyphicon glyphicon-minus"></span> EN</a>
					<a href="#" title="French" class="btn btn-default" role="button"><span class="glyphicon glyphicon-minus"></span> FR</a>
				</div>
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
			</div>-->
			<div class="clear"></div>
			
			<div id="bubble-container" class="top-margin">
				<center class="test"></center>
			</div>

	</div>
<div class="help-container">
	<a href="#" title="Need help?" class="btn btn-info btn-lg btn-help">Help?</a>
	<a href="#" title="Give us your feedback" class="btn btn-warning btn-lg btn-feedback">Feedback</a>
</div>
</div>

<script src="//d3js.org/d3.v3.min.js"></script>
<script>
	
	function bubbleMouseIn(bubble) {
		console.log('Mouse in: ' + this);
	}
	
	function bubbleMouseOut(bubble) {
		console.log('Mouse out: ' + this);
	}
	

	
	var diameter = (document.getElementById("bubble-container").offsetWidth)/2
		format = d3.format(",d"),
		color = d3.scale.category20c();

	var bubble = d3.layout.pack()
		.sort(null)
		.size([diameter, diameter])
		.padding(1.5);

	var svg = d3.select(".test").append("svg")
		.attr("width", diameter)
		.attr("height", diameter)
		.attr("class", "bubble");

	d3.json("flare.json", function(error, root) {
	  var node = svg.selectAll(".node")
		  .data(bubble.nodes(classes(root))
		  .filter(function(d) { return !d.children; }))
		.enter().append("g")
		  .attr("class", "node")
		  .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

	  node.append("title")
		  .text(function(d) { return d.className + ": " + format(d.value); });

	  node.append("circle")
		  .attr("r", function(d) { return d.r; })
		  .style("fill", function(d) { return color(d.packageName); });

	  /*node.append("text")
		  .attr("dy", ".3em")
		  .style("text-anchor", "middle")
		  .text(function(d) { return d.className.substring(0, d.r / 3); });*/
	});

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

	d3.select(self.frameElement).style("height", diameter + "px");
	
	console.log(jQuery('svg.bubble'));
	//jQuery(document).ready(function() {
		jQuery('svg.bubble g.node').hover(bubbleMouseIn, bubbleMouseOut);
	//});

</script>