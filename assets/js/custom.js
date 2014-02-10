jQuery(document).ready(function() {
	if (jQuery('#bubble-container').not('.bubbles-processed').addClass('bubbles-processed').length) {
		loadBubbles('views');
	}

});

var debug_ring_mode = 0;
var debug_text_mode = 0;
var debug_color_mode = 0;

function loadBubbles(sortBy, language) {

	// sortBy may be undefined. If so, don't call ajax/bubbles/undefined -_-
	var url = yd_settings.site_url + "ajax/bubbles";
	if (typeof(language) === 'undefined') {
		yd_settings.language_filter = null;
	}
	else {
		// Deprecated - to remove later once new filtering passes UAT
		url += '/' + language;
	}

	var diameter = (document.getElementById("bubble-container").offsetWidth);
	var format = d3.format(",.0f");
	var color = d3.scale.category20c();

	// Accepts nodes and computes the position of them for use by .data()
	var pack = d3.layout.pack()
		.sort(null)
		.size([diameter, diameter/2])
		.value(bubbles_values[sortBy])
		.padding(1.5);

	// Create the SVG bubble structure
	var svg = d3.select(".svg-container").html('').append("svg")
		.attr("width", diameter)
		.attr("height", diameter/2)
		.attr("class", "bubble");

	// Retrieve JSON from AJAX controller, but only once upon initial load
	d3.json(url, function(error, data) {
		console.log('creating bubbles sorted by ' + sortBy);

		// Select elements, even if they do not exist yet. enter() creates them and
		// appends them to the selection object. Then, we operate on them.
		var vis = svg.datum(data).selectAll('.node')
			.data(pack.nodes)
			.enter()
				.append('g')
				.attr("class", function(d) { return !d.children ? 'node-base' : 'node-parent'; })
				.attr("id", function(d) { return !d.children ? 'narrative-' + d.narrative_id : null; })
				.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
				.style('opacity', function(d) { narrative_matches_filter(d) ? 1 : 0.3; });
				// ^ the root g container is transformed, so for all children x and y is
				//   relative to 0

		// Title (text tooltip on hover)
		var titles = vis.append('title')
			.attr("x", function(d) { return d.x; })
			.attr("y", function(d) { return d.y; })
			.text(function(d) { return (d.children ? d.name : 'Narrative ' + d.narrative_id + ": " + format(d.value)); });

		// TODO: Expose these normally, and only show pie charts on hover
		var circles = vis.append("circle")
			.attr("r", function(d) { return d.r; })
			.attr("id", function(d) { return 'narrative-' + d.narrative_id; })
			.attr("class", function(d) { return !d.children ? 'node-base' : 'node-parent'; })
			.style("fill", bubble_fill_color)
			.style("opacity", function(d) { return !d.children ?  0.5: 1; });

		var legend = svg.append('g')
			.attr("x", 0)
			.attr("y", 0)
			.attr("class", 'legend')
			.attr("transform", function(d) { return 'translate(' + (diameter-250) + ',' +  25 +  ')'; })

		legend.append('text')
			.attr("dx", 0)
			.attr("dy", 0)
			.style("text-anchor", "left")
			.text('Should GMO foods be labeled?');

		d = {'position': 2};
		rect1 = legend.append("rect")
			.attr("x", 0)
			.attr("y", 5)
			.attr("width", 10)
			.attr("height", 10)
			.style("stroke", 'black')
			.style("fill", bubble_fill_color(d));

		d = {'position': 1};
		rect2 =legend.append("rect")
			.attr("x", 0)
			.attr("y", 25)
			.attr("width", 10)
			.attr("height", 10)
			.style("stroke", 'black')
			.style("fill", bubble_fill_color(d))

		legend.append("text")
			.style("font-size", "12px")
			.attr("x", 20)
			.attr("y", 15)
			.text("Yes");

		legend.append("text")
			.style("font-size", "12px")
			.attr("x", 20)
			.attr("y", 35)
			.text("No");

		// This computes the SVG path data required to form an arc.
		var arc = d3.svg.arc()
			.outerRadius(function(d) { return d.r; })
			.innerRadius(function(d) { return d.r*.8; });

		// This transforms simple data objects into a arc values from 0 to 2*pi
		var pie = d3.layout.pie()
			.value(function(d) { return d.value; })

		/**
		 * FIXME HACKY but works
		 * The arc generator above needs to know about the bubble radius, but it is
		 * only aware of what .data() has bound to it;
		 * namely, the arc segments calculated for us by the pie layout generator.
		 * So, let's just copy the data 'r' property into the pie data segments.
		 */
		function radiusmapper(d) {
			if (d.children) {
				return [];
			}
			var pie_data = pie(d.pie_data);
			pie_data.forEach(function(slice, i) {
				slice.r = d.r;
			});

			return pie_data;
		}

		// One SVG g container per pie chart slice
		var arcs_grey = vis.selectAll("g.slice-grey")
			.data(radiusmapper)
			.enter()
			.append("svg:g")
			.attr("class", "slice-grey");

		// One SVG g container per pie chart slice
		var arcs = vis.selectAll("g.slice")
			.data(radiusmapper)
			.enter()
			.append("svg:g")
			.attr("class", "slice")
			.style('display', 'none');

		// In the container, write a path based on the generated arc data
		var paths_grey = arcs_grey.append("svg:path")
			.attr("fill", function(d, i) { return '#dddddd'; } )
			.attr("d", arc);

		// In the container, write a path based on the generated arc data
		var paths = arcs.append("svg:path")
			.attr("fill", function(d, i) { return d.data.label == 'agrees' ? bubble_colors.red : bubble_colors.green; } )
			.attr("d", arc);

		// This comes after the paths so that the text doesn't get covered by the
		// path rendering
		var nodes = vis.append("text")
			.attr("dx", 0)
			.attr("dy", 0)
			.style("text-anchor", "middle")
			.text(bubbles_label_text[sortBy]);

		// Colorbox popup for audio player
		$(".node-base").click(function() {
			// Don't open colorbox for unmatched language filter
			if (!narrative_matches_filter(this.__data__)) {
				return false;
			}
			var colorbox = jQuery.colorbox({
				href: yd_settings.site_url + "narratives/" + this.__data__.narrative_id,
				left: 0,
				speed: 700,
				opacity: 0,
				onComplete : function() {
					$(this).colorbox.resize();
				}
			});
			loadMediaElement();
			colorbox.resize();
		});

		jQuery('.debug-rings input[type=radio]').click(function() {
			var lmode = jQuery(this).val();
			debugRingMode(lmode);
		});
		jQuery('#debug-ring-toggle-opacity').change(function() {
			var lmode = jQuery('.debug-rings input[type=radio]:checked').val();
			debugRingMode(lmode);
		});
		jQuery('.debug-text input[type=radio]').click(function() {
			var tmode = jQuery(this).val();
			debugTextMode(tmode);
		});
		jQuery('.debug-color input[type=radio]').click(function() {
			var cmode = jQuery(this).val();
			debugColorMode(cmode);
		});

		// 0 = hover
		// 1 = transparent
		// 2 = always
		function debugRingMode(lmode){
			// Set global
			debug_ring_mode = lmode;
			updateVis(sortBy);
		}
		// 0=none
		// 1=hover
		// 2=always
		function debugTextMode(tmode){
			// Set global
			debug_text_mode = tmode;
			updateVis(sortBy);
		}

		// 0=grey
		// 1=greys
		// 2=color
		function debugColorMode(cmode){
			// Set global
			debug_color_mode = cmode;
			updateVis(sortBy);
		}



		// Maps initial data to bubble pack
		updateVis(sortBy);

		/**
		 * Binds actual data to the DOM and provides a transition if a new ordering
		 * is preferred by user.
		 */
		function updateVis(sortBy2) {
			sortBy = sortBy2;
			console.log('updating bubbles to be sorted by ' + sortBy);

			pack.value(bubbles_values[sortBy]);
			pack.sort(null);
			var data1 = pack.nodes(data);

			vis.transition()
				.duration(700)
				.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
				.style('opacity', function(d) {
					if (yd_settings.language_filter) {
						return (d.language == yd_settings.language_filter ? 1 : 0.3);
					}
					return 1;
				});

			titles.attr("x", function(d) { return 0; })
				.attr("y", function(d) { return 0; })
				.text(function(d) { return (d.children ? d.name : 'Narrative ' + d['narrative_id'] + ": " + format(d.value)); });

			circles.transition()
					.duration(700)
					.style("fill", bubble_fill_color)
					.attr("r", function(d) { return d.r; });

			arcs_grey.data(radiusmapper)
			arcs.data(radiusmapper)

			paths_grey.data(radiusmapper)
			paths_grey.transition()
				.duration(700)
				.attr("d", function(d) { return arc(d); });

			paths.data(radiusmapper)
			paths.transition()
				.duration(700)
				.attr("d", function(d) { return arc(d); });

			nodes.text(bubbles_label_text[sortBy]);

			nodes.transition()
				.duration(700)
				.attr("dx", function(d) { return 0; })
				.attr("dy", function(d) { return 0; });


			// Normalize
			var bubble_opacity = parseFloat(jQuery('#debug-ring-toggle-opacity').val());
			jQuery('g.node-base').unbind('mouseenter mouseleave');
			jQuery('g.node-base').each(function() {
				jQuery('g.slice', this).hide();
				jQuery('g.slice-grey', this).hide();
				jQuery('g.slice', this).css('opacity', 1);
			});

			jQuery('g.node-base text').each(function() {
				jQuery(this).hide();
			})

			jQuery('g.legend').hide();

			// Debug-specific stuffs
			// Hover
			if (debug_ring_mode == 0) {
				jQuery('g.node-base').hover(
					function() { if (narrative_matches_filter(this.__data__)) { jQuery('g.slice', this).show(); }},
					function() { jQuery('g.slice', this).hide(); }
				);
			}
			// Transparent
			else if (debug_ring_mode == 1) {
				jQuery('g.node-base').each(function() {
					jQuery('g.slice-grey', this).show();
					jQuery('g.slice', this).css('opacity', bubble_opacity).show();
				});
				jQuery('g.node-base').hover(
					function() { if (narrative_matches_filter(this.__data__)) { jQuery('g.slice', this).css('opacity', 1); }},
					function() { jQuery('g.slice', this).css('opacity', bubble_opacity); }
				);
			}
			// Always
			else if (debug_ring_mode == 2) {
				jQuery('g.node-base').each(function() { jQuery('g.slice', this).show(); });
				jQuery('g.node-base').hover(
					function() { if (narrative_matches_filter(this.__data__)) { jQuery('circle', this).css('opacity', 0.8); }},
					function() { jQuery('circle', this).css('opacity', 0.5); }
				);
			}

			if (debug_text_mode == 0 || debug_text_mode == 1) {
				jQuery('g.node-base text').each(function() {
					jQuery(this).hide();
				});
			}
			if (debug_text_mode == 1) {
				jQuery('g.node-base').hover(
					function() { jQuery('text', this).show(); },
					function() { jQuery('text', this).hide(); }
				);

			}
			if (debug_text_mode == 2) {
				jQuery('g.node-base text').each(function() {
					jQuery(this).show();
				})
			}
			if (debug_color_mode == 2 || debug_color_mode == 3) {
				d = {'position': 2};
				rect1.style("fill", bubble_fill_color(d));
				d = {'position': 1};
				rect2.style("fill", bubble_fill_color(d));
				jQuery('g.legend').show();
			}
		}

		// Toggle buttons for navigation links
		jQuery('.sort-container .btn-group a').unbind('click').click(function() {
			jQuery('.sort-container .btn-group a').removeClass('active');
			jQuery(this).toggleClass('active');
			var sortBy = jQuery('.sort-container .btn-group a.active').attr('href').substring(1);
			updateVis(sortBy);
			return false;
		});

		// Toggle buttons for navigation links
		jQuery('.filter-container .btn-group a').unbind('click').click(function() {
			jQuery(this).toggleClass('active');
			jQuery('.filter-container .btn-group a').not(this).removeClass('active');
			var sortBy = jQuery('.sort-container .btn-group a.active').attr('href').substring(1);
			yd_settings.language_filter = jQuery('.filter-container .btn-group a.active').attr('href');
			updateVis(sortBy);
			return false;
		});

		d3.select(self.frameElement).style("height", diameter + "px");

	});
}

function dateFromString(str) {
	var a = $.map(str.split(/[^0-9]/), function(s) { return parseInt(s, 10); });
	return new Date(a[0], a[1]-1 || 0, a[2] || 1, a[3] || 0, a[4] || 0, a[5] || 0, a[6] || 0);
}

// +1 because 0 views/agrees/disagrees is valid state, but results in weird bubble rendering
// Coersion here; *ALWAYS* return integers
// TODO: normalization
bubbles_values = {
	'agrees': function(d) { return parseInt(d.agrees)+1; },
	'disagrees': function(d) { return parseInt(d.disagrees)+1; },
	'views': function(d) { return parseInt(d.views)+1; },
	'age': function(d) { var dcreated = dateFromString(d.created); var datenum = (dcreated.getFullYear()-2000)*1000 + dcreated.getMonth()/12*900 + dcreated.getDate()/31*100; console.log(d.created, datenum); return datenum },
	// TODO
	'popular': function(d) { return parseInt(d.narrative_id); }
};

bubbles_label_text = {
	'agrees': function(d) { return d.agrees; },
	'disagrees': function(d) { return d.disagrees; },
	'views': function(d) { return d.views; },
	'age': function(d) { return !d.children ? String(d.created).split(' ')[0] : null; },
	// TODO
	'popular': function(d) { return d.narrative_id; }
};

bubble_fill_color = function(d) {
	if (!d.children) {
		if (debug_color_mode == 0) {
			return bubble_colors.darkgrey
		}
		else if (debug_color_mode == 1) {
			switch (parseInt(d.position)) {
				case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
					return bubble_colors.darkgrey;

				case yd_settings.constants.NARRATIVE_POSITION_AGREE:
					return bubble_colors.darkgrey;

				case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
					return bubble_colors.darkergrey;
			}
		}
		else if (debug_color_mode == 3) {
			switch (parseInt(d.position)) {
				case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
					return bubble_colors.darkgrey;

				case yd_settings.constants.NARRATIVE_POSITION_AGREE:
					return bubble_colors.orange;

				case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
					return bubble_colors.blue;
			}
		}

		switch (parseInt(d.position)) {
			case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
				return bubble_colors.darkgrey;

			case yd_settings.constants.NARRATIVE_POSITION_AGREE:
				return bubble_colors.red;

			case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
				return bubble_colors.green;
		}
	}
	return bubble_colors.grey;
}

bubble_colors = {
	green: '#009933',
	red: '#CC0000',
	grey: '#eeeeee',
	darkgrey: '#777777',
	darkgrey: '#555555',
	blue: '#66a3d2',
	orange: '#ffc073'
}

function narrative_matches_filter(d) {
	return yd_settings.language_filter == null || yd_settings.language_filter == d.language;
}

function loadMediaElement() {
	if (jQuery('audio,video').not('player-processed').addClass('player-processed').length) {
		jQuery('audio,video').mediaelementplayer({
			// the order of controls you want on the control bar (and other plugins below)
			features: ['playpause','current','progress','duration','tracks','volume'],
			// show framecount in timecode (##:00:00:00)
			showTimecodeFrameCount: false
		 });

		//AJAX function that changes the picture according to the time of the
		//the audio.
		myaudio=document.getElementById("narrative_audio");
		myaudio.addEventListener('canplay', function() {
			// Player is ready
			myaudio.play();
		}, false);
		myaudio.addEventListener("timeupdate", function(e) {
			//document.getElementById('current-time').innerHTML = myaudio.currentTime;
			var xmlhttp=new XMLHttpRequest();
			xmlhttp.onreadystatechange=function() {
				if (xmlhttp.readyState==4 && xmlhttp.status==200) {
					document.getElementById("audioImage").src = xmlhttp.responseText;
				}
			};
			// TODO make this jQuery using jQuery.get()
			// TODO make this a real controller method
			var player = jQuery('.player-wrapper');
			if (player.length) {
				var narrative_id = player.attr('id').substring(10);
				var url = yd_settings.site_url + "ajax/audioImage/" + narrative_id + "/" + myaudio.currentTime;
				xmlhttp.open("GET", url, true);
				xmlhttp.send();
			}
		}, false);
	}
}
