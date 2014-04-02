if (typeof String.prototype.repeat != 'function') {
	String.prototype.repeat = function(num) {
		return new Array(num + 1).join(this);
	}
}
// See http://stackoverflow.com/questions/646628/how-to-check-if-a-string-startswith-another-string
if (typeof String.prototype.startsWith != 'function') {
	String.prototype.startsWith = function (str) {
		return this.slice(0, str.length) == str;
	};
}
if (typeof String.prototype.endsWith != 'function') {
	String.prototype.endsWith = function (str) {
		return this.slice(-str.length) == str;
	};
}

/**
 * Perform initializations, but only after DOM has completely loaded.
 */
jQuery(document).ready(function() {
	yd_settings.ui = {
		filters: {},
		sort: {},
		bubble_text_radius_cutoff: 35,
		transition_duration: 700,
		ring_inner_radius: 0.85,
		min_filtered_opacity: 0.00,
    min_sort_opacity: 0.2,
		bubble_fill_normal_mask: 0.5,
		bubble_fill_hover_mask: 0.8,
		system_colors: {
			green: '#5CB85C',
			darkgreen: '#007a28',
			red: '#D9534F',
			darkred: '#a30000',
			lightgrey: '#CFCFCF',
			grey: '#eeeeee',
			darkgrey: '#777777',
			darkdarkgrey: '#333333',
			blue: '#4282D3',
			darkblue: '#274e7e',
			purple: '#743CBC',
			darkpurple: '#452470',
			yellow: '#FFFF00',
			white: '#FFFFFF'
		},
		// glyphicons are a font, so to update this create a span glyphicon,
		// inspect its CSS to get the UTF-8 escape code. Next, use character
		// viewer (OSX) to insert it here.
		glyphicon_map: {
			// object properties here are the sort keys, not the glyphicon keys
			'agrees': '', // thumbs-up glyphicon
			'disagrees': '', // thumbs-down glyphicon
			'views': '', // headphones glyphicon
			//'views': '', // eye-open glyphicon
			'age': '', // time glyphicon
			//'popular': '', // fire glyphicon
			'popular': '', // star glyphicon
			'play': '', // play glyphicon
			'volume_down': '', // volume_up glyphicon
			'volume_up': '' // volume_down glyphicon
		}
	}

	// Add colorbox to any items with a "colorbox" class
	colorbox_initialize();

	// This will do nothing on most pages, but prepare any audio embeds we have
	// present on page load (i.e. on admin review narrative page)
	narrative_player_load();

	jQuery('audio,video').not('.player-processed').addClass('player-processed').each(function() {
		jQuery(this).mediaelementplayer({
		// the order of controls you want on the control bar (and other plugins below)
			features: ['playpause', 'current', 'progress', 'duration', 'tracks', 'volume'],
			// show framecount in timecode (##:00:00:00)
			showTimecodeFrameCount: false
		});
	});

	// Toggle buttons for navigation links
	jQuery('.language-container.btn-group a').click(function() {
		jQuery(this).toggleClass('active');
		//Diverting focus to an element other than the language button to handle IE no feedback of unclicking until loss of focus
		jQuery('.language-container').focus();
		jQuery('.language-container.btn-group a').not(this).removeClass('active');
    if (jQuery(this).hasClass('active')) {
      yd_settings.ui.filters.language = jQuery('.language-container.btn-group a.active').attr('href').substring(1);
    }
    else {
		  yd_settings.ui.filters.language = null;
    }
		return false;
	}).tooltip();

	// Toggle buttons for navigation links
	jQuery('.filter-container.btn-group a').click(function() {
		jQuery(this).toggleClass('active');
		jQuery('.filter-container.btn-group a').not(this).removeClass('active');
		var filter = jQuery(this).attr('href').substring(1);
		yd_settings.ui.filters[filter] = jQuery(this).hasClass('active');
		return false;
	}).tooltip();

	// This design pattern uses CSS classes to ensure that items aren't processed
	// twice by the same callback handler. It allows new DOM elements to be bound,
	// leaving existing ones untouched.
	if (jQuery('#bubble-container').not('.bubbles-processed').addClass('bubbles-processed').length) {
		// Initialize filter settings to defaults
		jQuery('.filter-container.btn-group a').each(function() {
			var filter = jQuery(this).attr('href').substring(1);
			yd_settings.ui.filters[filter] = jQuery(this).hasClass('active');
		}).tooltip();

		narrative_display_initialize();
	}

	// Radio-like toggle buttons for sort
	jQuery('.sort-container.btn-group a').click(function () {
		jQuery(this).toggleClass('active');
		jQuery('.sort-container.btn-group a').not(this).removeClass('active');
		yd_settings.ui.sort.criteria = jQuery(this).hasClass('active') ? jQuery(this).attr('href').substring(1) : null;

		// If we selected a new criteria, not untoggled
		if (jQuery(this).hasClass('active')) {
			var new_data = [];
			var positions = [yd_settings.constants.NARRATIVE_POSITION_NEUTRAL, yd_settings.constants.NARRATIVE_POSITION_AGREE, yd_settings.constants.NARRATIVE_POSITION_DISAGREE];
			positions.forEach(function(position, i) {
				var tmp = jQuery('.svg-container-' + position + ' svg').get(0);
				new_data = new_data.concat(tmp.__data__.children);
			});
			yd_settings.ui.sort.min = d3.min(new_data, narrative_sort_value);
			yd_settings.ui.sort.max = d3.max(new_data, narrative_sort_value);
		}
		return false;
	}).tooltip();
});

/**
* Method that gets called when the user mentionned a specific narrative to be played (bookmark or other)
*/
function initiate_player(id)
{
	//Creating click event
	var evt = document.createEvent("MouseEvents");
		evt.initMouseEvent("click", true, true, window, 1, 0, 0, 0, 0, false, false, false, false, 0, null);

		//If the element exist, simulate click
		var narrative = document.getElementById("narrative-" + id);
		if(narrative != null)
		{
		narrative.dispatchEvent(evt);
	}
}

/**
 * Binds a colorbox callback to links with a 'colorbox' class. Result is magic
 * so that simply adding the class to a link makes it open in in a popup.
 */
function colorbox_initialize() {
	jQuery('a')
	.filter('.colorbox:not(.colorbox-processed)')
	.addClass('colorbox-processed')
	.colorbox();
}

/**
 * (Re)initialize the narrative homepage display, to include bubble areas
 * and history bar.
 */
function narrative_display_initialize() {
	jQuery('.sort-container.btn-group a').unbind('click');
	jQuery('.svg-container').html('');

	narrative_bubbles_load(yd_settings.constants.NARRATIVE_POSITION_NEUTRAL);
	narrative_bubbles_load(yd_settings.constants.NARRATIVE_POSITION_AGREE);
	narrative_bubbles_load(yd_settings.constants.NARRATIVE_POSITION_DISAGREE);
	narrative_history_load();
}

/**
 * Accessor compatible .map() to determine non-normalized value from data object
 */
function narrative_bubble_value(d, i) {
	return parseInt(d.agrees) + parseInt(d.disagrees);
}

/**
 * Accessor compatible .map() to determine non-normalized value from data object
 */
function narrative_sort_value(d, i) {
	if (yd_settings.ui.sort.criteria == 'age') {
		return date_from_string(d.uploaded);
	}
	else {
		return parseInt(d[yd_settings.ui.sort.criteria]);
	}
}

/**
 * Normalization puts all entries in a range of 0 - 1, then multiplies by a
 * factor to achieve a scaled version. A common base is added to all results.
 */
function narrative_bubbles_standardize(data, factor, base) {
	var min = d3.min(data, narrative_bubble_value);
	var max = d3.max(data, narrative_bubble_value);
	var diff = max - min;

	data.forEach(function(d, i) {
		d.value = (diff == 0 ? 1 : (narrative_bubble_value(d, i) - min) / diff);
		d.value *= factor;
		d.value += base;
	});
}

/**
 * Loads bubbles for the indicated language & position.
 */
function narrative_bubbles_load(position) {
	var svgselect;

	// yd_settings.sort_by may be undefined. If so, don't call ajax/bubbles/undefined -_-
	var url = yd_settings.site_url + "ajax/bubbles";
	if (typeof(position) === 'undefined' || position === null) {
		svgselect = ".svg-container-1";
	}
	else {
		svgselect = ".svg-container-" + position;
		url += '/' + position;
	}

	var num_parent_bubbles = jQuery('#bubble-container .svg-container').length;
	var diameter = jQuery("#bubble-container").width();
	var width = diameter/num_parent_bubbles-4;
	var height = diameter/num_parent_bubbles;
	var color = d3.scale.category20c();

	// Accepts nodes and computes the position of them for use by .data()
	// normally we'd set .value() to an inline function with some formula, but
	// we're using d.value as precalculated by the standardization function.
	var pack = d3.layout.pack()
		.sort(null)
		.size([width, height])
		.value(function(d) { return d.value; })
		.padding(5);

	// Create the SVG bubble structure
	var svg = d3.select(svgselect).html('').append("svg")
		.attr("width", width)
		.attr("height", height)
		.attr("class", "bubble");

	// Retrieve JSON from AJAX controller, but only once upon initial load
	d3.json(url, function(error, data) {
		console.log('creating bubbles for SVG ' + svgselect);

		// Sets the d.value attribute to something normalized
		narrative_bubbles_standardize(data.children, 250, 25);

		// Select elements, even if they do not exist yet. enter() creates them and
		// appends them to the selection object. Then, we operate on them.
		var vis = svg.datum(data).selectAll('g.node')
			.data(pack.nodes)
			.enter()
				.append('g')
				.attr("class", function(d) { return 'node ' + (!d.children ? 'node-base' : 'node-parent'); })
				.attr("id", function(d) { return !d.children ? 'narrative-' + d.narrative_id : null; })
				.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
				.style("opacity", function(d) { return narrative_matches_filter(d) ? narrative_sort_opacity(d) : yd_settings.ui.min_filtered_opacity; });
				// ^ the root g container is transformed, so for all children x and y is
				//   relative to 0

		/*var positionLabel = svg.append('text')
			.attr("dx", width/2)
			.attr("dy", 25)
			.style("text-anchor", "middle")
			.style("font-size", "2em")
			.text(position_label_text(position));*/

		narrative_draw_bubbles(vis);
		narrative_bind_player(svgselect);
		narrative_bubbles_update(svgselect);

		// Toggle buttons for navigation links
		jQuery('.controls-container .btn-group a').click(function() {
			narrative_bubbles_update(svgselect);
			return false;
		});

		d3.select(self.frameElement).style("height", diameter + "px");
	});
}

/**
 * Alter any bubble attributes necessary and transition their attribute or
 * styling changes.
 */
function narrative_bubbles_update(svgselect) {
	console.log('updating bubbles for SVG ' + svgselect);

	var format = d3.format(",.0f");
	var svg = d3.select(svgselect).select('svg');
	var vis = svg.selectAll('g.node');

	//to update pack values, use: pack.value(function() { return metric; });
	//to update data, use: pack.nodes(data)

	vis.transition().duration(yd_settings.ui.transition_duration)
		.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
		.style("opacity", function(d) { return narrative_matches_filter(d) ? narrative_sort_opacity(d) : yd_settings.ui.min_filtered_opacity; });

	vis.selectAll('circle')
		.transition().duration(yd_settings.ui.transition_duration)
		.style("fill", bubble_fill_color)
		.attr("r", function(d) { return d.r; });

	var arcs = vis.selectAll('g.slice')
		.data(narrative_data_radiusmapper)

	vis.selectAll('tspan.node-label-agrees')
		.text(function(d) { return d.agrees; });

	vis.selectAll('tspan.node-label-disagrees')
		.text(function(d) { return d.disagrees; });

	// Circle fill hover
	jQuery(svgselect + ' g.node-base').hover(
		function() { if (narrative_matches_filter(this.__data__)) { jQuery('circle', this).css('opacity', 0.7); }},
		function() { jQuery('circle', this).css('opacity', yd_settings.ui.bubble_fill_normal_mask); }
	);
}

/**
 * The arc generator needs to know about the bubble radius, but it is only aware
 * of what .data() has bound to it; namely, the arc segments calculated for us
 * by the pie layout generator. It doesn't have access to the "parent" data
 * (i.e. the full narrative), which is what it needs to grab the circle radius.
 * So, let's just copy the data 'r' property into the pie data segments for use
 * later. Ugly, but works.
 */
function narrative_data_radiusmapper(d) {
	// This transforms simple data objects into a arc values from 0 to 2*pi
	var pie = d3.layout.pie()
		.sort(null)
		.value(function(d) { return d.value; });

	if (d.children) {
		return [];
	}

	var pie_data = pie(d.pie_data);
	pie_data.forEach(function(slice, i) {
		slice.r = d.r;
	});

	return pie_data;
}

/**
 * Draw bubbles on the provided vis (SVG). vis should already have its d3 data
 * bound to it.
 */
function narrative_draw_bubbles(vis) {
	var format = d3.format(",.0f");

	// Title (text tooltip on hover)
	var titles = vis.append('title')
		.attr("x", function(d) { return d.x; })
		.attr("y", function(d) { return d.y; })
		.text(function(d) { return (d.children ? d.name : 'Narrative ' + d.narrative_id); });

	var circles = vis.append("circle")
		.attr("r", function(d) { return d.r; })
		.attr("class", function(d) { return !d.children ? 'node-base' : 'node-parent'; })
		.style("fill", bubble_fill_color)
		.style("opacity", function(d) { return !d.children ? yd_settings.ui.bubble_fill_normal_mask : 1; })
		.style("cursor", function(d) { return d.children ?  "normal" : "pointer"; });

		// This computes the SVG path data required to form an arc.
	var arc = d3.svg.arc()
		.outerRadius(function(d) { return d.r; })
		.innerRadius(function(d) { return  d.r*yd_settings.ui.ring_inner_radius; });

	// One SVG g container per pie chart slice
	var arcs = vis.selectAll("g.slice")
		.data(narrative_data_radiusmapper)
		.enter()
		.append("svg:g")
		.attr("class", "slice")
		.style("cursor", function(d) { return d.children ?  "normal" : "pointer"; });

	// In the container, write a path based on the generated arc data
	var paths = arcs.append("svg:path")
		.attr("fill", function(d, i) { return d.data.label == 'agrees' ? yd_settings.ui.system_colors.green : yd_settings.ui.system_colors.red; } )
		.attr("d", arc);

	// This comes after the paths so that the text doesn't get covered by the
	// path rendering
	var label_agree = vis.filter(function(d, i) { return !d.children && d.r > yd_settings.ui.bubble_text_radius_cutoff }).append("text")
		.attr('dy', '-0.6em')
		.style("text-anchor", "middle")
		.style("cursor", "pointer");
	label_agree.append('svg:tspan')
		.attr('class', 'node-label-agrees')
		.text(function(d) { return d.agrees; })
		.style("dominant-baseline", "central")
	label_agree.append('svg:tspan')
		.text(yd_settings.ui.glyphicon_map.agrees)
		.attr('dx', '0.3em')
		.style("dominant-baseline", "central")
		.style("font-family", "'Glyphicons Halflings'")
		.style('fill', yd_settings.ui.system_colors.green);

	var label_disagree = vis.filter(function(d, i) { return !d.children && d.r > yd_settings.ui.bubble_text_radius_cutoff }).append("text")
		.attr('dy', '0.6em')
		.style("text-anchor", "middle")
		.style("cursor", "pointer")
		.style("display", function(d) { return null; });
	label_disagree.append('svg:tspan')
		.attr('class', 'node-label-disagrees')
		.text(function(d) { return d.disagrees; })
		.style("dominant-baseline", "central")
	label_disagree.append('svg:tspan')
		.text(yd_settings.ui.glyphicon_map.disagrees)
		.attr('dx', '0.3em')
		.style("dominant-baseline", "central")
		.style('font-family', "'Glyphicons Halflings'")
		.style('fill', yd_settings.ui.system_colors.red);
}

/**
 * Make the API call to add narrative to the user's history given a narrative
 * data object.
 */
function narrative_history_add(d)
{
	var url = yd_settings.site_url + 'ajax/add_history/' + d.narrative_id;
	jQuery.getJSON(url)
		.done(function(data) {
			narrative_history_load();
		})
		.fail(function() {
			alert("There was an error adding this narrative to your history list.");
		});
}

/**
 * Retrieves the user's history and loads the history bar.
 */
function narrative_history_load()
{
	var url = yd_settings.site_url + 'ajax/get_history';
	jQuery.getJSON(url)
		.done(function(data) {
			var num_parent_bubbles = jQuery('#bubble-container .svg-container').length;
			var diameter = jQuery("#bubble-container").width();
			var width = jQuery("#recent-container").width();
			jQuery(svgselect).height(jQuery("#recent-container").height());
			var height = jQuery("#recent-container").height() - 20; // for scrollbar
			// Get the limiting dimension (width or height), subtract desired padding,
			// then divide by 5 to get history circle radius
			var padding = 10;
			var radius = Math.min(height - padding*2, (width - 2*yd_settings.constants.NARRATIVE_HISTORY_VISIBLE*padding) / yd_settings.constants.NARRATIVE_HISTORY_VISIBLE) / 2;
			var radius_padding = radius + padding;
			var svgselect = '#recent-container .svg-container';
			console.log('loading history bubbles for SVG ' + svgselect);

			var svg = d3.select(svgselect).html('').append("svg")
				.attr("width", radius_padding * 2*data.length)
				.attr("height", height)
				.attr("class", "bubble");

			// This sets each bubble to be a static size. If the code between the
			// standardization comments below is left uncommented, it will then get
			// scaled for each set of narratives of a given position in history.
			data.forEach(function(d, i) { d.value = radius; });

			// Standardize history bubble data foreach narrative position independently
			var new_data = [];
			var types = [yd_settings.constants.NARRATIVE_POSITION_NEUTRAL, yd_settings.constants.NARRATIVE_POSITION_AGREE, yd_settings.constants.NARRATIVE_POSITION_DISAGREE];
			types.forEach(function(type, i) {
				var tmp = data.filter(function(d) { return d.position == type; });
				narrative_bubbles_standardize(tmp, radius*3/4, radius/4);
				new_data = new_data.concat(tmp);
			});

			// Concatenation will mess up the ordering. Merge arrays and resort.
			var original_order = data.map(function(d) { return d.narrative_id; });
			function history_sort_func(a, b) {
				var a_pos = original_order.indexOf(a.narrative_id);
				var b_pos = original_order.indexOf(b.narrative_id);
				if (a_pos == b_pos) {
					return 0;
				}
				return a_pos < b_pos ? -1 : 1;
			}
			data = new_data.sort(history_sort_func);
			// End standardization of bubble data

			/**
			 * Callback for d3's .data() which populates the x, y, and r attributes.
			 * Effectively, this is a custom d3 layout callback.
			 */
			function narrative_history_data(data, i)
			{
				data.forEach(function(d, i) {
					// Find out where the previous bubble ended.
					var previous_x = 0;
					if (data[i-1]) {
						previous_x = data[i-1].x + data[i-1].r;
					}
					// Add our radius and padding to that position for next bubble
					d.r = d.value;
					d.x = previous_x + padding + d.r;
					d.y = (height-d.r*2)/2 + d.r;
				});
				return data;
			}

			var vis = svg.datum(data).selectAll('g.node')
				.data(narrative_history_data)
				.enter()
					.append('g')
					.attr("class", function(d) { return 'node ' + (!d.children ? 'node-base' : 'node-parent'); })
					.attr("id", function(d) { return !d.children ? 'history-narrative-' + d.narrative_id : null; })
					.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
					// ^ the root g container is transformed, so for all children x and y is
					//   relative to 0

			narrative_draw_bubbles(vis);
			narrative_bind_player(svgselect);

			jQuery(svgselect + " g.node-base").hover(
				function() {
					d3.select(this).select('circle').style('fill', yd_settings.ui.system_colors.yellow);
					d3.select('#narrative-' + this.__data__.narrative_id).select('circle').style('fill', yd_settings.ui.system_colors.yellow);
				},
				function() {
					d3.select(this).select('circle').style('fill', bubble_fill_color);
					d3.select('#narrative-' + this.__data__.narrative_id).select('circle').style('fill', bubble_fill_color);
				}
			);

		});
}

/**
 * Setup DOM bindings so that the narrative player can be initialized when
 * clicking a node under the SVG specified in 'svgselect'.
 */
function narrative_bind_player(svgselect) {
	// Don't open colorbox for unmatched language filter
	jQuery(svgselect + " g.node-base").click(function(e) {
		// TODO: disabled for now
		// Only allow popup if (a) narrative matches filter or (b) is in history bar
	 if (! (narrative_matches_filter(this.__data__) || jQuery(this).attr('id').startsWith('history-'))) {
		  return false;
		}

		// Call method to add narrative to history
		narrative_history_add(this.__data__);

		// Modify address bar without reloading page
		// See https://developer.mozilla.org/en-US/docs/Web/Guide/API/DOM/Manipulating_the_browser_history
		// FIXME HTML5 ONLY
		var stateObj = {};
		history.pushState(stateObj, "Narrative " + this.__data__.narrative_id, yd_settings.site_url + "narratives/" + this.__data__.narrative_id);

		// Both are here because we need to reset the fill style for both the
		// clicked bubble (which could be in the history bar) and the actual
		// narrative bubble in the main display
		this.__data__.viewed = 1;
		d3.select(this).select('circle').style("fill", bubble_fill_color);
		d3.select('#narrative-' + this.__data__.narrative_id)
			.style("opacity", function(d) { return narrative_matches_filter(d) ? narrative_sort_opacity(d) : yd_settings.ui.min_filtered_opacity; })
			.select('circle')
				.style('fill', bubble_fill_color);

		var narrative_url = "player/" + this.__data__.narrative_id;

		// Colorbox popup for audio player
		var image_update_timer;
		var colorbox = jQuery.colorbox({
			href: yd_settings.site_url + narrative_url,
			width: 890,
			speed: 700,
			onComplete: function() {

				//Registering loading of the narrative in the colorbox - Commented out because Google analytics was tracking 1 load of /narrative/# AND 1 load of /player/# (offsetting percentages)
				//_gaq.push(['_trackPageview', narrative_url]);

				narrative_player_load();
				narrative_player_buttons_initialize();
				jQuery(this).colorbox.resize();
			},
			onCleanup: function() {
				clearInterval(image_update_timer);

				// Cleanup MediaElement player - ensures audio stops playing if it
				// loaded a hidden plug-in for compatibility shim
				var player = jQuery("#colorbox audio,#colorbox video")
				if (player.length && player.data('mediaelementplayer')) {
					player.data('mediaelementplayer').pause();
					player.data('mediaelementplayer').remove();
				}


        //In the event that there is not media element, do not collect analytics,
        // TO_DO: proper fix
        if(document.getElementById("DNE") != null) {
          return;
        }
        else
				{
          //Notify Google Analytics of partial or full play
  				if(document.getElementsByName("fullPlay")[0].value == "true")
  				{
  					_gaq.push(['_trackPageview', narrative_url + "/full"]);
  				}
  				else
  				{
  					_gaq.push(['_trackPageview', narrative_url + "/partial"]);
            console.log("blahh");
  				}

  				//Notify Google Analytics of agree or disagree or nothing
  				if(document.getElementsByName("opinion")[0].value == "agree")
  				{
  					_gaq.push(['_trackPageview', narrative_url + "/agree"]);
  				}
  				else if(document.getElementsByName("opinion")[0].value == "disagree")
  				{
  					_gaq.push(['_trackPageview', narrative_url + "/disagree"]);
  				}

  				//Notify Google Analytics of bookmarking or nothing
  				if(document.getElementsByName("bookmark")[0].value == "true")
  				{
  					_gaq.push(['_trackPageview', narrative_url + "/bookmark"]);
  				}

  				//Notify Google Analytics of sharing or nothing
  				if(document.getElementsByName("share")[0].value == "true")
  				{
  					_gaq.push(['_trackPageview', narrative_url + "/share"]);
  				}
        }
			},
			onClosed: function() {
				// Modify address bar without reloading page
				// See https://developer.mozilla.org/en-US/docs/Web/Guide/API/DOM/Manipulating_the_browser_history
				// FIXME HTML5 ONLY
				var stateObj = {};
				history.pushState(stateObj, "Home", yd_settings.site_url);
			}
		});
		//increment the number of views in the database
		var url = yd_settings.site_url + "ajax/increment_views/" + this.__data__.narrative_id;
		jQuery.post(url)
			.fail(function() {
				alert("There was an registering your vote on this narrative.");
			});
	});

	var id = jQuery('input[name=toPlay]').val();
	jQuery(svgselect + " #narrative-" + id).not('.bookmark-play').addClass('bookmark-play').trigger('click');
}

/**
 * Converts the DB date string into a JS date object.
 */
function date_from_string(str) {
	var a = jQuery.map(str.split(/[^0-9]/), function(s) { return parseInt(s, 10); });
	return new Date(a[0], a[1]-1 || 0, a[2] || 1, a[3] || 0, a[4] || 0, a[5] || 0, a[6] || 0);
}

/**
 * Returns text label for the position constants.
 */
function position_label_text(position) {
	switch (position) {
		case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
			return 'Neutral / Ambivalent';
		case yd_settings.constants.NARRATIVE_POSITION_AGREE:
			return 'For / Pour';
		case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
			return 'Against / Contre';
		default:
			return null;
	}
}

/**
 * Maps a narrative object to its fill color.
 */
bubble_fill_color = function(d) {
	color = d.viewed ? 'dark' : '';
	color = ''
	if (d.children) {
		return yd_settings.ui.system_colors.white;
	}
	switch (parseInt(d.position)) {
		case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
			color += 'darkgrey';
			break;

		case yd_settings.constants.NARRATIVE_POSITION_AGREE:
			color += 'purple';
			break;

		case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
			color += 'blue';
			break;
		default:
			color = 'lightgrey';
			break;
	}
	return yd_settings.ui.system_colors[color];
}

/**
 * Examines the current filter settings stored in yd_settings and determines if
 * the provided narrative object matches the filter or not.
 */
function narrative_matches_filter(d) {
	if (d.children) {
		return true;
	}

	var history = true;
	if (yd_settings.ui.filters.history) {
		history = !d.viewed;
	}

	var language = true;
	if (yd_settings.ui.filters.language) {
		// FIXME case sensitivity
		language = yd_settings.ui.filters.language == d.language.toLowerCase();
	}

	return language && history;
}

/**
 * Examines the current filter settings stored in yd_settings and determines if
 * the provided narrative object matches the filter or not.
 */
function narrative_sort_opacity(d) {
	if (d.children) {
		return true;
	}

	factor = 1;
	if (yd_settings.ui.sort.criteria == 'age') {
		var min_ms = yd_settings.ui.sort.min.getTime();
		var max_ms = yd_settings.ui.sort.max.getTime();
		var current = date_from_string(d.uploaded).getTime();
		factor = (current - min_ms) / (max_ms - min_ms);
	}
	else if (yd_settings.ui.sort.criteria == 'agrees') {
		factor = (d.agrees - yd_settings.ui.sort.min)/ (yd_settings.ui.sort.max - yd_settings.ui.sort.min)
	}
	else if (yd_settings.ui.sort.criteria == 'disagrees') {
		factor = (d.disagrees - yd_settings.ui.sort.min)/ (yd_settings.ui.sort.max - yd_settings.ui.sort.min)
	}
	else {
		factor = 1;
	}
	// Scaling: http://stackoverflow.com/questions/5294955/how-to-scale-down-a-range-of-numbers-with-a-known-min-and-max-value
	return (1 - yd_settings.ui.min_sort_opacity) * factor + yd_settings.ui.min_sort_opacity;
}

/**
 * Loads the narrative player once the popup page with audio is ready.
 */
function narrative_player_load() {
	var player_wrappers = jQuery('.player-wrapper').not('player-processed');
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
		// NOTE: e.timeStamp() is not consistent - see http://stackoverflow.com/questions/18197401/javascript-event-timestamps-not-consistent
		var alreadyPlayed = false;
		myaudio.addEventListener('canplay', function(e) {
			player_last_update = Date.now();
			narrative_player_update_image(myaudio.currentTime);
			if (jQuery(this).hasClass('autoplay') && alreadyPlayed == false) {
				alreadyPlayed = true;
				myaudio.play();
			}
		}, false);

		// Update as the audio continues to play.
		var listenedTime = 0;
		var lastTime = 0;
		var skippedTime = 0;
		myaudio.addEventListener('timeupdate', function(e) {
			var time_now = Date.now();
			if (time_now - player_last_update > yd_settings.constants.NARRATIVE_PLAYER_IMAGE_UPDATE_INTERVAL) {
				player_last_update = time_now;
				narrative_player_update_image(myaudio.currentTime);
			}

			//Detection of absolute full play
			var difference = myaudio.currentTime - lastTime;

			if(difference > 1 || difference < 0)
			{
				//Skip detected
				skippedTime += difference;
			}
			else
			{
				listenedTime += difference;
			}

			if(skippedTime <= 0 && (listenedTime + skippedTime) > myaudio.duration - 1)
			{
				//This constitutes a full play of the narrative
				document.getElementsByName("fullPlay")[0].value = "true";
			}

			lastTime = myaudio.currentTime;
		}, false);
	}
}

/**
 * Updates the narrative player's image given a timecode.
 */
function narrative_player_update_image(timecode) {
	var player = jQuery('.player-wrapper');
	if (!player.length) {
		// No narrative player was loaded.
		return;
	}
	var narrative_id = player.attr('id').substring(10);
	var url = yd_settings.site_url + "ajax/audio_image/" + narrative_id + "/" + timecode;
	jQuery.get(url, function(data) {
		jQuery("#audio_image").attr('src', data);
	});
}

/**
 * Bind the necessary callbacks to enable AJAX commenting.
 */
function initialize_commenting() {
	// Click handler: Comment (root level)
	jQuery(".action-comment-post").not('.comment-processed').addClass('comment-processed').click(function() {
		var narrative_id = jQuery('#new-comment-form input[name=narrative_id]').val();
		var uri = "comments/reply/" + narrative_id;
		var url = yd_settings.site_url + uri;
		var formdata = jQuery("#new-comment-form").serialize();
		jQuery.post(url, formdata)
			.done(function(data) {
				//Notify Google Analytics of addition of comment root level
				_gaq.push(['_trackPageview', "/" + uri]);

				// Remove the 'no comment' message if it exists
				jQuery('.comments-wrapper .remove-me').remove();
				// Add the new comment, pre-rendered by the controller
				jQuery(data).prependTo('.comments-wrapper').hide().slideDown();
				jQuery("#new-comment").val('');
				initialize_commenting();
			})
			.fail(function() {
				alert("An error occurred while adding your comment. Please try again.");
			});
	});

	//Click handler: Load reply form
	jQuery(".action-comment-reply").not('.comment-processed').addClass('comment-processed').click(function() {
		var parent_body = jQuery(this).parent().siblings('.comment-body').text();
		var parent_id = jQuery(this).parent().siblings('.comment-id').val();
		var url = yd_settings.site_url + 'comments/reply_form/' + parent_id + '/' + parent_body;
		jQuery.post(url, function(data) {
			if(jQuery(".reply").length)
			{
				jQuery(".reply").remove();
			}
			jQuery(data).prependTo('.comments-wrapper').hide().slideDown();
			$('.comments-wrapper').animate({ scrollTop: 0}, 1000);
			initialize_commenting();
		});
	});

	// Click handler: submit a reply to a comment
	//jQuery(".action-reply-post").not('.comment-processed').addClass('comment-processed').click(function() {
	jQuery(".action-reply-post").not('.comment-processed').addClass('comment-processed').click(function() {
		var narrative_id = jQuery('#new-comment-form input[name=narrative_id]').val();
		var parent_id = jQuery(this).parent().siblings(".parent-id").val();
		//alert(narrative_id + " " + parent_id);
		var uri = "comments/reply/" + narrative_id + '/' + parent_id;
		var url = yd_settings.site_url + uri;
		var formdata = jQuery("#new-reply-form").serialize();
		jQuery.post(url, formdata)
			.done(function(data) {
				//Notifying Google Analytics of commenting on a comment
				_gaq.push(['_trackPageview', "/" + uri]);

				// Remove the 'reply box if it exists
				jQuery('.reply').remove();
				// Add the new comment, pre-rendered by the controller
				jQuery(data).prependTo('.comments-wrapper').hide().slideDown();
				//jQuery("#new-comment").val('');
				initialize_commenting();
			})
			.fail(function() {
				alert("An error occurred while replying to the comment. Please try again.");
			});
	});
	// Click handler: Flag (on comment)
	jQuery(".action-comment-report").not('.comment-processed').addClass('comment-processed').click(function() {
		var comment_id = jQuery(this).parents('.comment').attr('id').substring(8);
		var uri = "comments/flag/" + comment_id;
		var url = yd_settings.site_url + uri;
		var formdata = jQuery("#new-comment-form").serialize();
		jQuery.post(url, formdata)
			.done(function(data) {
				//Notify Google Analytics of comment flagging
				_gaq.push(['_trackPageview', "/" + uri]);

				jQuery("#new-comment").val('');
				jQuery('#comment-' + comment_id + ' .action-comment-report').css('color', 'red');
				alert("Thank you, this comment has been reported.");
			})
			.fail(function() {
				alert("An error occurred while reporting the comment. Please try again.");
			});
	});

	jQuery(".quote").hover(
		function(){
			var parent_id = jQuery(this).attr("parent-id");
			jQuery(this).parent().siblings("div[my-id='"+parent_id+"']").stop().animate({"background-color": "#FFFF80"}, 100);//.css("background", "#FFFF80");
		},
		function(){
			var parent_id = jQuery(this).attr("parent-id");
			jQuery(this).parent().siblings("div[my-id='"+parent_id+"']").stop().animate({"background-color": "#EDEFF4"}, 1300);//.css("background", "#EDEFF4");
		}
	);

	jQuery(".quote").click(function(){
		var parent_id = jQuery(this).attr("parent-id");
		var comment_element = jQuery(this).parent().siblings("div[my-id='"+parent_id+"']");
		var comment_index = jQuery(".comment").index(comment_element);
		$('.comments-wrapper').scrollTo(comment_element, 800);
	});

	//show reply and flag on hover
	jQuery(".comment").not('.comment-processed').addClass('comment-processed').hover(
		function(){
			jQuery(this).children(".actions").children().stop().fadeIn("fast");
		},
		function(){
			jQuery(this).children(".actions").children().stop().fadeOut("fast");
		});

	/*Post comment upon clicking enter
	 *Kinda Hacky*/
	jQuery('.comments-container #new-comment-form .form-control').not('.comment-processed').addClass('comment-processed').keypress(function (e)
	{
		var key = e.which;
		if(key == 13)  // the enter key code
		{
			jQuery(this).siblings('.action-comment-post').click();
			return false;
		}
	});
}

/**
 * Binds callbacks to the narrative player's buttons (voting, sharing, etc).
 */
function narrative_player_buttons_initialize()
{
	var client = new ZeroClipboard( document.getElementById("copy-share"), {
	  moviePath: yd_settings.site_url + "assets/zeroclipboard/ZeroClipboard.swf"
	} );

	client.on( "load", function(client) {
	   //alert( "movie is loaded" );

	  client.on( "complete", function(client, args) {
	    // `this` is the element that was clicked
	    //this.style.display = "none";
	    alert("Copied text to clipboard: " + args.text );
	  });
	});
	//get narrative ID
	var player_wrappers = jQuery(".player-wrapper");
	if (!player_wrappers.length)
	{
		return;
	}
	var nar_id = player_wrappers.attr('id').substring(10);

	//Handle flagging of narrative
	jQuery(".action-narrative-report").not('.flag-clicked').addClass('flag-not-clicked').click(function() {
		var uri = "player/flag/" + nar_id;
		var url = yd_settings.site_url + uri;
		//jQuery(this).removeClass("action-narrative-report");
		if(jQuery(this).hasClass("flag-clicked"))
		{
			return;
		}
		else
		{
			jQuery.post(url)
			.done(function() {
				//Notify Google Analytics of narrative flagging
				_gaq.push(['_trackPageview', "/" + uri]);

				jQuery(".action-narrative-report").css('color', 'red');
				jQuery(".action-narrative-report").text("Narrative Reported");
				jQuery(".action-narrative-report").removeClass("flag-not-clicked")
				jQuery(".action-narrative-report").addClass('flag-clicked');
			})
			.fail(function() {
				alert("An error occurred while reporting the narrative. Please try again.")
			});
		}

	});

	//Handle bookmarking of narrative
	jQuery(".bookmark-btn").click(function() {
		document.getElementsByName("bookmark")[0].value = "true"; //To notify Google analytics of the bookmark action
		add_bookmark();
	}).tooltip();

	//handle sharing action
	jQuery(".share-btn").click(function() {
		document.getElementsByName("share")[0].value = "true"; //To notify Google analytics of the share action
		if (!jQuery(this).hasClass('active')) {
      jQuery(this).addClass('active');
      show_share_url();
    }
    else {
    	jQuery(this).removeClass('active');
    	jQuery(this).blur();
    	show_share_url();
    }
	}).tooltip();

	//local var to decide agree/disagree
	var last_consensus = "";

	//If agree or disagree button is pressed
	jQuery(".player-buttons .float-right .btn-group .btn").click(function() {
		var clicked = this;
		var new_consensus = jQuery(this).attr('href').substring(1);
		var current_agrees = parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text()));
		var current_disagrees = parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text()));
		var url = "";

		jQuery(".player-buttons .float-right .btn-group .btn").addClass('disabled')

		//Increment the agrees, decrement the disagrees
		if(last_consensus == "agree" && new_consensus == "disagree")
		{
			url = yd_settings.site_url + "ajax/toggle_agree_to_disagree/" + nar_id;
			current_agrees -= 1;
			current_disagrees += 1;
			document.getElementsByName("opinion")[0].value = "agree";
		}
		//Increment the disagrees, decrement the agrees
		else if (last_consensus == "disagree" && new_consensus == "agree")
		{
			url = yd_settings.site_url + "ajax/toggle_disagree_to_agree/" + nar_id;
			current_agrees += 1;
			current_disagrees -= 1;
			document.getElementsByName("opinion")[0].value = "disagree";
		}
		//Increment the disagrees or agrees
		else if (last_consensus == "")
		{
			url = yd_settings.site_url + "ajax/increment_agrees_disagrees/" + nar_id + "/" + new_consensus;
			if (new_consensus == "agree")
			{
				current_agrees += 1;
				document.getElementsByName("opinion")[0].value = "agree";
			}
			else if (new_consensus == "disagree")
			{
				current_disagrees += 1;
				document.getElementsByName("opinion")[0].value = "disagree";
			}
		}
		else if (last_consensus == new_consensus)
		{
			url = yd_settings.site_url + "ajax/decrement_agrees_disagrees/" + nar_id + "/" + new_consensus;
			if (new_consensus == "agree")
			{
				current_agrees -= 1;
				document.getElementsByName("opinion")[0].value = "null";
			}
			else if (new_consensus == "disagree")
			{
				current_disagrees -= 1;
				document.getElementsByName("opinion")[0].value = "null";
			}
		}

		// After whatever it was we did, update consensus
		jQuery.post(url)
			.done(function(data) {
				jQuery(".player-buttons .float-right .btn-group .btn")
					.removeClass('disabled')
					.not(clicked)
					.removeClass('active btn-primary');

				jQuery(clicked).toggleClass('active btn-primary');

				last_consensus = "";
				var active = jQuery(".player-buttons .float-right .btn-group .btn").filter(".active");
				if (active.length) {
					last_consensus = active.attr('href').substring(1);
				}

				jQuery(".player-stats .float-right .green.text").text(current_agrees + " ");
				jQuery(".player-stats .float-right .red.text").text(current_disagrees + " ");
				update_concensus_bar(current_agrees, current_disagrees);

				jQuery('#narrative-' + nar_id)[0].__data__.agrees = current_agrees;
				jQuery('#narrative-' + nar_id)[0].__data__.disagrees = current_disagrees;

				jQuery('#history-narrative-' + nar_id)[0].__data__.agrees = current_agrees;
				jQuery('#history-narrative-' + nar_id)[0].__data__.disagrees = current_disagrees;

				var vis = d3.selectAll('.svg-container').selectAll('svg');
				vis.selectAll('tspan.node-label-agrees')
					.text(function(d) { return d.agrees; });
				vis.selectAll('tspan.node-label-disagrees')
					.text(function(d) { return d.disagrees; });
			})
			.fail(function() {
				alert("An error occurred while voting.");
			});
	}).tooltip();

	function update_concensus_bar(agrees, disagrees)
	{
		var total_votes = Math.max(agrees + disagrees, 1);
		var new_agrees = Math.round(agrees/total_votes) * 100;
		var new_disagrees = Math.round(disagrees/total_votes) * 100;
		jQuery(".progress-bar progress-bar-success").width(new_agrees);
		jQuery(".progress-bar progress-bar-danger").width(new_disagrees);
	}

	function fade_in_success_message(input)
	{
		jQuery(".success-message").text(input).fadeIn();
	}

	function fade_out_success_message()
	{
		jQuery(".success-message").fadeOut();
	}
}

function add_bookmark()
{
	alert('Please press Control+D to bookmark this page; your browser does not support automatic bookmark creation.');
}

function show_share_url(){
	jQuery(".link-content").toggle();
	jQuery(this).colorbox.resize();
}
