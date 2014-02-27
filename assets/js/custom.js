String.prototype.repeat = function(num) {
	return new Array(num + 1).join(this);
}

var debug_ring_mode;
var debug_text_mode;
var debug_text_content_mode;
var debug_color_mode;
var debug_position_mode;
var debug_ring_opacity;
var debug_ring_radius;
var debug_bubble_opacity;
var debug_recent_sort;

jQuery(document).ready(function() {
	// Add colorbox to any items with a "colorbox" class
	initalizeColorBox();

	// This will do nothing on most pages, but prepare any audio embeds we have
	// present on page load (i.e. on admin review narrative page)
	narrative_player_load();

	// Process bubbles
	debug_ring_mode = parseInt(jQuery('.debug-rings input:checked').val());
	debug_text_mode = parseInt(jQuery('.debug-text input:checked').val());
	debug_text_content_mode = parseInt(jQuery('.debug-text-content input:checked').val());
	debug_color_mode = parseInt(jQuery('.debug-color input:checked').val());
	debug_position_mode = parseInt(jQuery('.debug-position input:checked').val());
	debug_ring_opacity = parseFloat(jQuery('#debug-ring-toggle-opacity').val());
	debug_ring_radius = parseFloat(jQuery('#debug-ring-toggle-radius').val());
	debug_bubble_opacity = parseFloat(jQuery('#debug-bubble-toggle-opacity').val());
	debug_recent_sort = parseFloat(jQuery('.debug-recent-sort input:checked').val());

	// Toggle buttons for navigation links
	jQuery('.filter-container.btn-group a').click(function() {
		jQuery(this).toggleClass('active');
		jQuery('.filter-container.btn-group a').not(this).removeClass('active');
		if (debug_recent_sort == 0) {
			yd_settings.sort_by = jQuery('.sort-container.btn-group a.active').attr('href').substring(1);
		}
		yd_settings.language_filter = jQuery('.filter-container.btn-group a.active').attr('href');
		return false;
	});

	if (jQuery('#bubble-container').not('.bubbles-processed').addClass('bubbles-processed').length) {
		yd_settings.sort_by = jQuery('.sort-container.btn-group a.active').attr('href').substring(1);
		reloadBubbles();
	}

	// Radio-like toggle buttons for sort
	jQuery('.sort-container.btn-group a').click(function () {
		if (debug_recent_sort == 0) {
			jQuery('.sort-container.btn-group a').removeClass('active');
			jQuery(this).addClass('active');
			yd_settings.recent_filter = null;
			yd_settings.sort_by = jQuery('.sort-container.btn-group a.active').attr('href').substring(1);
		}
		else {
			jQuery(this).toggleClass('active');
			yd_settings.recent_filter = jQuery('.sort-container.btn-group a[href="#age"]').hasClass('active');
		}
		return false;
	});
});

function initalizeColorBox() {
	jQuery('a, area, input')
    .filter('.colorbox:not(.initColorbox-processed)')
    .addClass('initColorbox-processed')
    .colorbox();
}

function reloadBubbles() {
	jQuery('.debug input').unbind('click change');
	jQuery('.sort-container.btn-group a').unbind('click');
	jQuery('.svg-container').html('');
	if (debug_position_mode == 0) {
		loadBubbles(null, null);
	}
	else {
		loadBubbles(null, 0);
		loadBubbles(null, 1);
		loadBubbles(null, 2);
	}
}

function loadBubbles(language, position) {
	var svgselect;

	// yd_settings.sort_by may be undefined. If so, don't call ajax/bubbles/undefined -_-
	var url = yd_settings.site_url + "ajax/bubbles";
	if (typeof(language) === 'undefined' || language === null) {
		yd_settings.language_filter = null;
	}
	else {
		// Deprecated - to remove later once new filtering passes UAT
		//url += '/' + language;
		yd_settings.language_filter = language;
	}
	if (typeof(position) === 'undefined' || position === null) {
		svgselect = ".svg-container-1";
	}
	else {
		svgselect = ".svg-container-" + position;
		url += '/' + position;
	}
	yd_settings.recent_filter = null;

	var num_parent_bubbles = jQuery('.svg-container').length;
	var diameter = (document.getElementById("bubble-container").offsetWidth);
	var format = d3.format(",.0f");
	var color = d3.scale.category20c();

	// Accepts nodes and computes the position of them for use by .data()
	var pack = d3.layout.pack()
		.sort(null)
		.size([debug_position_mode == 0 ? diameter : diameter/num_parent_bubbles-4, diameter/num_parent_bubbles])
		.value(bubbles_values[yd_settings.sort_by])
		.padding(5);

	// Create the SVG bubble structure
	var svg = d3.select(svgselect).html('').append("svg")
		.attr("width", debug_position_mode == 0 ? diameter : diameter/num_parent_bubbles-4)
		.attr("height", diameter/num_parent_bubbles)
		.attr("class", "bubble");

	// Retrieve JSON from AJAX controller, but only once upon initial load
	d3.json(url, function(error, data) {
		console.log('creating bubbles sorted by ' + yd_settings.sort_by);

		// Select elements, even if they do not exist yet. enter() creates them and
		// appends them to the selection object. Then, we operate on them.
		var vis = svg.datum(data).selectAll('.node')
			.data(pack.nodes)
			.enter()
				.append('g')
				.attr("class", function(d) { return !d.children ? 'node-base' : 'node-parent'; })
				.attr("id", function(d) { return !d.children ? 'narrative-' + d.narrative_id : null; })
				.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
				.style("opacity", function(d) { return narrative_matches_filter(d) ? 1 : debug_bubble_opacity; });
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
			.style("opacity", function(d) { return !d.children ?  0.5: 1; })
			.style("cursor", function(d) { return d.children ?  "normal" : "pointer"; });

		var positionLabel = svg.append('g')
			.attr("transform", function(d) { return 'translate(' + 215 + ',' +  25 +  ')'; })
			.append('text')
				.attr("dx", 0)
				.attr("dy", 0)
				.style("text-anchor", "left")
				.style("font-size", "2em")
				.text(position_label_text(position));

		// FIXME && false debugging
		if ((position == null || position == 1) && false) {
			var legend = svg.append('g')
				.attr("x", 0)
				.attr("y", 0)
				.attr("class", 'legend')
				.attr("transform", function(d) { return 'translate(' + 100 + ',' +  450 +  ')'; })

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
		}

		// This computes the SVG path data required to form an arc.
		var arc = d3.svg.arc()
			.outerRadius(function(d) { return d.r; })
			.innerRadius(function(d) { return d.r*debug_ring_radius; });

		// This transforms simple data objects into a arc values from 0 to 2*pi
		var pie = d3.layout.pie()
			.sort(null)
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
			.attr("fill", function(d, i) { return d.data.label == 'agrees' ? bubble_colors.green : bubble_colors.red; } )
			.attr("d", arc);

		// This comes after the paths so that the text doesn't get covered by the
		// path rendering
		var nodes = vis.append("text")
			.attr("dx", 0)
			.attr("dy", 6)
			.attr("width", function(d) { return d.r - 10; })
			.style("text-anchor", "middle")
			.text(bubbles_label_text(yd_settings.sort_by))
			.style('font-family', debug_text_content_mode == 0 ? "'Helvetica Neue', Helvetica, Arial, sans-serif;" : "'Glyphicons Halflings'")
			.style('letter-spacing', debug_text_content_mode == 0 ? 'normal' : '5px')
			.style('font-size', debug_text_content_mode == 0 ? '1em' : '0.8em')
			.style("cursor", "pointer");

		// Colorbox popup for audio player
		jQuery(svgselect + " g.node-base").click(function(e) {
			// Don't open colorbox for unmatched language filter
			if (!narrative_matches_filter(this.__data__)) {
				return false;
			}
			var image_update_timer;
			var colorbox = jQuery.colorbox({
				href: yd_settings.site_url + "narratives/" + this.__data__.narrative_id,
				left: 0,
				speed: 700,
				opacity: 0,
				onComplete: function() {
					narrative_player_load();
					jQuery(this).colorbox.resize();
				},
				onClosed: function() {
					clearInterval(image_update_timer);
				}
			});
		});

		jQuery('.debug-rings input[type=radio]').click(function() {
			var lmode = jQuery(this).val();
			debugRingMode(lmode);
		});
		jQuery('#debug-ring-toggle-opacity,#debug-ring-toggle-radius').change(function() {
			var lmode = jQuery('.debug-rings input[type=radio]:checked').val();
			debug_ring_opacity = parseFloat(jQuery('#debug-ring-toggle-opacity').val());
			debug_ring_radius = parseFloat(jQuery('#debug-ring-toggle-radius').val());
			debugRingMode(lmode);
		});
		jQuery('#debug-bubble-toggle-opacity').change(function() {
			var lmode = jQuery('.debug-rings input[type=radio]:checked').val();
			debug_bubble_opacity = parseFloat(jQuery(this).val());
			debugRingMode(lmode);
		});
		jQuery('.debug-text input[type=radio]').click(function() {
			var tmode = jQuery(this).val();
			debugTextMode(tmode);
		});
		jQuery('.debug-text-content input[type=radio]').click(function() {
			var tcmode = jQuery(this).val();
			debugTextContentMode(tcmode);
		});
		jQuery('.debug-color input[type=radio]').click(function() {
			var cmode = jQuery(this).val();
			debugColorMode(cmode);
		});
		jQuery('.debug-position input[type=radio]').click(function(event) {
			var pmode = jQuery(this).val();
			debugPositionMode(pmode);
			event.stopImmediatePropagation();
		});
		jQuery('.debug-recent-sort input[type=radio]').click(function() {
			var rsmode = jQuery(this).val();
			if (rsmode == 0) {
				yd_settings.sort_by = 'agrees';
				yd_settings.recent_filter = null;
				jQuery('.sort-container.btn-group a').removeClass('active');
				jQuery('.sort-container.btn-group a[href="#agrees"]').addClass('active');
			}
			debugRecentSortMode(rsmode);
		});

		// 0 = hover
		// 1 = transparent
		// 2 = always
		function debugRingMode(lmode){
			// Set global
			debug_ring_mode = lmode;
			updateVis(svgselect);
		}
		// 0=none
		// 1=hover
		// 2=always
		function debugTextMode(tmode){
			// Set global
			debug_text_mode = tmode;
			updateVis(svgselect);
		}

		// 0=metric
		// 1=glyph
		// 2=glyphhover
		function debugTextContentMode(tcmode){
			// Set global
			debug_text_content_mode = tcmode;
			updateVis(svgselect);
		}

		// 0=grey
		// 1=greys
		// 2=color
		// 3=color (alternate)
		// 4=hue
		function debugColorMode(cmode){
			// Set global
			debug_color_mode = cmode;
			updateVis(svgselect);
		}

		// 0=single
		// 1=multi
		function debugPositionMode(pmode){
			// Set global
			debug_position_mode = pmode;
			reloadBubbles();
		}

		// 0=sort
		// 1=filter
		function debugRecentSortMode(rsmode){
			// Set global
			debug_recent_sort = rsmode;
			updateVis(svgselect);
		}



		// Maps initial data to bubble pack
		updateVis(svgselect);

		/**
		 * Binds actual data to the DOM and provides a transition if a new ordering
		 * is preferred by user.
		 */
		function updateVis(svgselect) {
			console.log('updating bubbles to be sorted by ' + yd_settings.sort_by);

			pack.value(bubbles_values[yd_settings.sort_by]);
			pack.sort(null);
			var data1 = pack.nodes(data);

			vis.transition()
				.duration(700)
				.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
				.style("opacity", function(d) { return narrative_matches_filter(d) ? 1 : debug_bubble_opacity; });

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

			nodes.text(bubbles_label_text(yd_settings.sort_by))
				.style('font-family', debug_text_content_mode == 0 ? "'Helvetica Neue', Helvetica, Arial, sans-serif;" : "'Glyphicons Halflings'")
				.style('letter-spacing', debug_text_content_mode == 0 ? 'normal' : '5px')
				.style('font-size', debug_text_content_mode == 0 ? '1em' : '0.8em');

			nodes.transition()
				.duration(700)
				.attr("dx", 0)
				.attr("dy", 6)


			// Normalize
			debug_ring_opacity = parseFloat(jQuery('#debug-ring-toggle-opacity').val());
			debug_ring_radius = parseFloat(jQuery('#debug-ring-toggle-radius').val());
			debug_bubble_opacity = parseFloat(jQuery('#debug-bubble-toggle-opacity').val());
			jQuery(svgselect + ' g.node-base').unbind('mouseenter mouseleave');
			jQuery(svgselect + ' g.node-base').each(function() {
				jQuery('g.slice', this).hide();
				jQuery('g.slice-grey', this).hide();
				jQuery('g.slice', this).css('opacity', 1);
			});

			jQuery(svgselect + ' g.node-base text').each(function() {
				jQuery(this).hide();
			});

			jQuery(svgselect + ' g.legend').hide();

			// Debug-specific stuffs
			// Hover
			if (debug_ring_mode == 0) {
				jQuery(svgselect + ' g.node-base').hover(
					function() { if (narrative_matches_filter(this.__data__)) { jQuery('g.slice', this).show(); }},
					function() { jQuery('g.slice', this).hide(); }
				);
			}
			// Transparent
			else if (debug_ring_mode == 1) {
				jQuery(svgselect + ' g.node-base').each(function() {
					jQuery('g.slice-grey', this).css('opacity', debug_ring_opacity).show();
					jQuery('g.slice', this).css('opacity', debug_ring_opacity).show();
				});
				jQuery(svgselect + ' g.node-base').hover(
					function() {
						if (narrative_matches_filter(this.__data__)) {
							jQuery('g.slice', this).css('opacity', 1);
							jQuery('g.slice-grey', this).css('opacity', 1);
						}
					},
					function() {
						jQuery('g.slice', this).css('opacity', debug_ring_opacity);
						jQuery('g.slice-grey', this).css('opacity', debug_ring_opacity);
					}
				);
			}
			// Always
			else if (debug_ring_mode == 2) {
				jQuery(svgselect + ' g.node-base').each(function() { jQuery('g.slice', this).show(); });
				jQuery(svgselect + ' g.node-base').hover(
					function() { if (narrative_matches_filter(this.__data__)) { jQuery('circle', this).css('opacity', 0.7); }},
					function() { jQuery('circle', this).css('opacity', 0.5); }
				);
			}
			// Always+Anti-hover
			else if (debug_ring_mode == 3) {
				jQuery(svgselect + ' g.node-base').each(function() { jQuery('g.slice', this).show(); });
				jQuery(svgselect + ' g.node-base').hover(
					function() { if (narrative_matches_filter(this.__data__)) { jQuery('g.slice', this).hide(); }},
					function() { jQuery('g.slice', this).show(); }
				);
			}

			if (debug_text_mode == 0 || debug_text_mode == 1) {
				jQuery(svgselect + ' g.node-base text').each(function() {
					jQuery(this).hide();
				});
			}
			if (debug_text_mode == 1) {
				jQuery(svgselect + ' g.node-base').hover(
					function() { jQuery('text', this).show(); },
					function() { jQuery('text', this).hide(); }
				);
			}
			if (debug_text_mode == 2) {
				jQuery(svgselect + ' g.node-base text').each(function() {
					jQuery(this).show();
				})
			}
			if (debug_color_mode == 2 || debug_color_mode == 3) {
				d = {'position': 2};
				//rect1.style("fill", bubble_fill_color(d));
				d = {'position': 1};
				//rect2.style("fill", bubble_fill_color(d));
				jQuery(svgselect + ' g.legend').show();
			}

			if (debug_text_content_mode == 2) {
				jQuery(svgselect + ' g.node-base').hover(
					function() { d3.select(this).selectAll('text').text(function(d) { return d.children ? null : glyphicon_map.play }); },
					function() { d3.select(this).selectAll('text').text(bubbles_label_text(yd_settings.sort_by)); }
				);
			}
			if (debug_text_content_mode == 3) {
				var timer;

				function animate_1(item) {
					d3.select(item).selectAll('text')
						.text(glyphicon_map.volume_down)
						.style("font-size", "1.1em")
						.attr("dy", 8);
					timer = setTimeout(function() { animate_2(item); }, 400);

				}

				function animate_2(item) {
					d3.select(item).selectAll('text')
						.text(glyphicon_map.volume_up)
						.style("font-size", "1.1em")
						.attr("dy", 8);
					timer = setTimeout(function() { animate_1(item); }, 400);
				}

				nodes.text(function(d) { return d.children ? null : glyphicon_map.play });

				jQuery(svgselect + ' g.node-base').hover(
					function() {
						animate_1(this);
					},
					function() {
						clearTimeout(timer);
						d3.select(this).selectAll('text')
							.text(function(d) { return d.children ? null : glyphicon_map.play })
							.attr("dy", 6)
							.style("font-size", "0.8em");
					}
				);
			}
			if (debug_text_content_mode == 4) {
				var timer;

				function animate_4(item) {
					d3.select(item).selectAll('text')
						.text(glyphicon_map.play)
					timer = setTimeout(function() { animate_3(item); }, 800);

				}

				function animate_3(item) {
					d3.select(item).selectAll('text')
						.text(bubbles_label_text(yd_settings.sort_by))
					timer = setTimeout(function() { animate_4(item); }, 2000);
				}

				nodes.text(function(d) { return d.children ? null : glyphicon_map.play });

				jQuery(svgselect + ' g.node-base').hover(
					function() {
						animate_3(this);
					},
					function() {
						clearTimeout(timer);
						d3.select(this).selectAll('text')
							.text(glyphicon_map.play)
					}
				);
			}
		}

		// Toggle buttons for navigation links
		jQuery('.sort-container.btn-group a').click(function() {
			updateVis(svgselect);
			return false;
		});

		// Toggle buttons for navigation links
		jQuery('.filter-container.btn-group a').click(function() {
			updateVis(svgselect);
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
	'age': function(d) { var duploaded = dateFromString(d.uploaded); var datenum = (duploaded.getFullYear()-2013)*1000 + duploaded.getMonth()/12*900 + duploaded.getDate()/31*50; return datenum },
	// TODO
	'popular': function(d) { return parseInt(d.narrative_id); }
};

function bubbles_label_text(metric) {
	if (debug_text_content_mode == 0) {
		return bubbles_label_text_0[metric];
	}
	else {
		return bubbles_label_text_1;
	}
}

bubbles_label_text_0 = {
	'agrees': function(d) { return d.children ? null : d.agrees; },
	'disagrees': function(d) { return d.children ? null : d.disagrees; },
	'views': function(d) { return d.children ? null : d.views; },
	'age': function(d) { return d.children ? null : String(d.uploaded).split(' ')[0]; },
	// TODO
	'popular': function(d) { return d.children ? null : d.narrative_id; }
};

function position_label_text(position) {
	switch (position) {
		case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
			return 'Neutral';
		case yd_settings.constants.NARRATIVE_POSITION_AGREE:
			return 'For';
		case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
			return 'Against';
		default:
			return null;
	}
}

function bubble_get_multiplier(d) {
	if (d.children) {
		return null;
	}

	var multiplier = 1;
	if (d.r > 20 && d.r <= 30) {
		multiplier = 2;
	}
	else if (d.r > 30 && d.r <= 40) {
		multiplier = 3;
	}
	else if (d.r > 40 && d.r <= 50) {
		multiplier = 4;
	}
	else if (d.r > 50) {
		multiplier = 5;
	}

	return multiplier;
}

function bubbles_label_text_1(d) {
	if (d.children) {
		return null;
	}
	var multiplier = bubble_get_multiplier(d);
	var total = parseInt(d.agrees) + parseInt(d.disagrees);
	//return glyphicon_map[yd_settings.sort_by].repeat(multiplier);

	var agree_glyphs = Math.round(multiplier * parseInt(d.agrees) / Math.max(total, 1), 0);
	var disagree_glyphs = Math.round(multiplier * parseInt(d.disagrees) / Math.max(total, 1), 0);

	return glyphicon_map['agrees'].repeat(agree_glyphs) + glyphicon_map['disagrees'].repeat(disagree_glyphs);
}

// object properties here are the sort keys, not the glyphicon keys
glyphicon_map = {
	'agrees': '', // thumbs-up
	'disagrees': '', // thumbs-down
	'views': '', // headphones
	//'views': '', // eye-open
	'age': '', // time
	//'popular': '', // fire
	'popular': '', // star
	'play': '', // play
	'volume_down': '', // volume_up
	'volume_up': '' // volume_down
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
					return bubble_colors.purple;

				case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
					return bubble_colors.blue;
			}
		}
		else if (debug_color_mode == 4) {
			var color = d3.scale.linear()
				.domain([0.1, 1, 3])
				.range(["red", "grey", "green"]);
			return color(Math.min(3, (d.agrees+1) / (d.disagrees+1)));
		}

		switch (parseInt(d.position)) {
			case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
				return bubble_colors.darkgrey;

			case yd_settings.constants.NARRATIVE_POSITION_AGREE:
				return bubble_colors.green;

			case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
				return bubble_colors.red;
		}
	}
	return bubble_colors.lightgrey;
}

bubble_colors = {
	green: '#009933',
	red: '#CC0000',
	lightgrey: '#CFCFCF',
	grey: '#eeeeee',
	darkgrey: '#777777',
	darkergrey: '#333333',
	blue: '#4282D3',
	purple: '#743CBC'
}

function narrative_matches_filter(d) {
	var recent = true;
	if (!d.children && yd_settings.recent_filter) {
		var today = new Date();
		today.setDate(today.getDate() - 7);
		var dateStr = today.getFullYear() + '-' + (today.getMonth()+1) + '-' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
		var todayData = {
			uploaded: dateStr
		};
		recent = bubbles_values.age(d) > bubbles_values.age(todayData);
	}
	return (yd_settings.language_filter == null || yd_settings.language_filter == d.language) && recent;
}

function narrative_player_load() {
	var player_wrappers = jQuery('.player-wrapper').not('player-processed')
	if (player_wrappers.length) {
		player_wrappers.addClass('player-processed')
		jQuery('audio,video', player_wrappers).mediaelementplayer({
			// the order of controls you want on the control bar (and other plugins below)
			features: ['playpause', 'current', 'progress', 'duration', 'tracks', 'volume'],
			// show framecount in timecode (##:00:00:00)
			showTimecodeFrameCount: false
		 });

		//AJAX function that changes the picture according to the time of the
		//the audio.
		var player_last_update = (new Date).getTime();
		var myaudio = document.getElementById("narrative_audio");
		// Update when the audio is ready to play (at load or after seeking)
		myaudio.addEventListener('canplay', function(e) {
			player_last_update = e.timeStamp;
			narrative_player_update_image(myaudio.currentTime);
			//myaudio.play();
		}, false);
		// Update as the audio continues to play.
		myaudio.addEventListener('timeupdate', function(e) {
			if (e.timeStamp - player_last_update > yd_settings.NARRATIVE_PLAYER_IMAGE_UPDATE_INTERVAL) {
				player_last_update = e.timeStamp;
				narrative_player_update_image(myaudio.currentTime);
			}
		}, false);
	}
}

function narrative_player_update_image(timecode) {
	var player = jQuery('.player-wrapper');
	if (!player.length) {
		// No narrative player was loaded.
		return;
	}
	console.log('start update for timecode ' + timecode);
	var narrative_id = player.attr('id').substring(10);
	var url = yd_settings.site_url + "ajax/audio_image/" + narrative_id + "/" + timecode;
	jQuery.get(url, function(data) {
		jQuery("#audio_image").attr('src', data);
		console.log('done update for timecode ' + timecode, data);
	});
}
