String.prototype.repeat = function(num) {
	return new Array(num + 1).join(this);
}

/**
 * Perform initializations, but only after DOM has completely loaded.
 */
jQuery(document).ready(function() {
	yd_settings.ui = {
		filters: {},
		transition_duration: 700,
		ring_inner_radius: 0.8,
		filtered_opacity: 0.2,
		bubble_fill_normal_mask: 0.5,
		bubble_fill_hover_mask: 0.8,
		system_colors: {
			green: '#009933',
			darkgreen: '#007a28',
			red: '#CC0000',
			darkred: '#a30000',
			lightgrey: '#CFCFCF',
			grey: '#eeeeee',
			darkgrey: '#777777',
			darkdarkgrey: '#333333',
			blue: '#4282D3',
			darkblue: '#274e7e',
			purple: '#743CBC',
			darkpurple: '#452470',
			yellow: '#FFFF00'
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
	jQuery('.filter-container.btn-group a').click(function() {
		jQuery(this).toggleClass('active');
		jQuery('.filter-container.btn-group a').not(this).removeClass('active');
		yd_settings.ui.filters.language = jQuery('.filter-container.btn-group a.active').attr('href');
		return false;
	});

	// This design pattern uses CSS classes to ensure that items aren't processed
	// twice by the same callback handler. It allows new DOM elements to be bound,
	// leaving existing ones untouched.
	if (jQuery('#bubble-container').not('.bubbles-processed').addClass('bubbles-processed').length) {
		jQuery('.sort-container.btn-group a').each(function() {
			var filter = jQuery(this).attr('href').substring(1);
			yd_settings.ui.filters[filter] = jQuery(this).hasClass('active');
		});

		narrative_display_initialize();
	}

	// Radio-like toggle buttons for sort
	jQuery('.sort-container.btn-group a').click(function () {
		jQuery(this).toggleClass('active');
		var filter = jQuery(this).attr('href').substring(1)
		yd_settings.ui.filters[filter] = jQuery(this).hasClass('active');
		return false;
	});
	
	//Function that gets called to load narrative if the user is using a bookmark or other
	//HACKY: 2s delay to account for the loading of all the SVG objects needed to simulate click on them.
	setTimeout
	(
		function()
		{
			initiate_player(document.getElementsByName("toPlay")[0].value);
		},
		2000
	);
});

/**
*	Method that gets called when the user mentionned a specific narrative to be played (bookmark or other)
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
	var pack = d3.layout.pack()
		.sort(null)
		.size([width, height])
		.value(function(d) { return d.agrees + d.disagrees; })
		.padding(5);

	// Create the SVG bubble structure
	var svg = d3.select(svgselect).html('').append("svg")
		.attr("width", width)
		.attr("height", height)
		.attr("class", "bubble");

	// Retrieve JSON from AJAX controller, but only once upon initial load
	d3.json(url, function(error, data) {
		console.log('creating bubbles for SVG ' + svgselect);

		// Select elements, even if they do not exist yet. enter() creates them and
		// appends them to the selection object. Then, we operate on them.
		var vis = svg.datum(data).selectAll('g.node')
			.data(pack.nodes)
			.enter()
				.append('g')
				.attr("class", function(d) { return 'node ' + (!d.children ? 'node-base' : 'node-parent'); })
				.attr("id", function(d) { return !d.children ? 'narrative-' + d.narrative_id : null; })
				.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
				.style("opacity", function(d) { return narrative_matches_filter(d) ? 1 : yd_settings.ui.filtered_opacity; });
				// ^ the root g container is transformed, so for all children x and y is
				//   relative to 0

		var positionLabel = svg.append('text')
			.attr("dx", width/2)
			.attr("dy", 25)
			.style("text-anchor", "middle")
			.style("font-size", "2em")
			.text(position_label_text(position));

		narrative_draw_bubbles(vis);
		narrative_bind_player(svgselect);
		narrative_bubbles_update(svgselect);

		// Toggle buttons for navigation links
		jQuery('.sort-container.btn-group a').click(function() {
			narrative_bubbles_update(svgselect);
			return false;
		});

		// Toggle buttons for navigation links
		jQuery('.filter-container.btn-group a').click(function() {
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
		.style("opacity", function(d) { return narrative_matches_filter(d) ? 1 : yd_settings.ui.filtered_opacity; });

	vis.selectAll('circle')
		.transition().duration(yd_settings.ui.transition_duration)
		.style("fill", bubble_fill_color)
		.attr("r", function(d) { return d.r; });

	var arcs = vis.selectAll('g.slice')
		.data(narrative_data_radiusmapper)

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
		.innerRadius(function(d) { return d.r*yd_settings.ui.ring_inner_radius; });

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
	var label_agree = vis.filter(function(d, i) { return !d.children && d.r > 35 }).append("text")
		.attr('dy', '-0.6em')
		.style("text-anchor", "middle")
		.style("cursor", "pointer");
	label_agree.append('svg:tspan')
		.text(function(d) { return d.agrees; })
		.style("dominant-baseline", "central")
	label_agree.append('svg:tspan')
		.text(yd_settings.ui.glyphicon_map.agrees)
		.attr('dx', '0.3em')
		.style("dominant-baseline", "central")
		.style('font-family', "'Glyphicons Halflings")
		.style('fill', yd_settings.ui.system_colors.green);

	var label_disagree = vis.filter(function(d, i) { return !d.children && d.r > 35 }).append("text")
		.attr('dy', '0.6em')
		.style("text-anchor", "middle")
		.style("cursor", "pointer")
		.style("display", function(d) { return null; });
	label_disagree.append('svg:tspan')
		.text(function(d) { return d.disagrees; })
		.style("dominant-baseline", "central")
	label_disagree.append('svg:tspan')
		.text(yd_settings.ui.glyphicon_map.disagrees)
		.attr('dx', '0.3em')
		.style("dominant-baseline", "central")
		.style('font-family', "'Glyphicons Halflings")
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

			/**
			 * Callback for d3's .data() which populates the x, y, and r attributes.
			 * Effectively, this is a custom d3 layout callback.
			 */
			function narrative_history_data(data, i)
			{
				data.forEach(function(d, i) {
					d.x = i*radius_padding*2 + radius_padding;
					d.y = (height-radius*2)/2 + radius;
					d.r = radius;
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
		//if (! (narrative_matches_filter(this.__data__) || jQuery(this).attr('id').indexOf('history-') === 0)) {
		//	return false;
		//}

		// Call method to add narrative to history
		narrative_history_add(this.__data__);

		// Both are here because we need to reset the fill style for both the
		// clicked bubble (which could be in the history bar) and the actual
		// narrative bubble in the main display
		this.__data__.viewed = 1;
		d3.select(this).select('circle').style("fill", bubble_fill_color);
		d3.select('#narrative-' + this.__data__.narrative_id)
			.style("opacity", function(d) { return narrative_matches_filter(d) ? 1 : yd_settings.ui.filtered_opacity; })
			.select('circle')
				.style('fill', bubble_fill_color);

		var narrative_url = "narratives/" + this.__data__.narrative_id;

		// Colorbox popup for audio player
		var image_update_timer;
		var colorbox = jQuery.colorbox({
			href: yd_settings.site_url + narrative_url,
			left: 0,
			speed: 700,
			opacity: 0,
			onComplete: function() {

				//Registering loading of the narrative in the colorbox
				_gaq.push(['_trackPageview', narrative_url]);

				narrative_player_load();
				jQuery(this).colorbox.resize();
			},
			onClosed: function() {
				clearInterval(image_update_timer);
			}
		});
		//increment the number of views in the database
		var url = yd_settings.site_url + "ajax/increment_views/" + this.__data__.narrative_id;
		jQuery.post(url)
			.fail(function() {
				alert("There was an registering your vote on this narrative.");
			});
	});
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
		return yd_settings.ui.system_colors.lightgrey;
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

	var recent = true;
	if (yd_settings.ui.filters.age) {
		var today = new Date();
		today.setDate(today.getDate() - 7);
		var dateStr = today.getFullYear() + '-' + (today.getMonth()+1) + '-' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
		recent = date_from_string(d.uploaded) > date_from_string(dateStr);
	}

	var popular = true;
	if (yd_settings.ui.filters.popular) {
		popular = d.agrees / Math.max(d.disagrees, 1) > 1.5;
	}

	var agrees = true;
	if (yd_settings.ui.filters.agrees) {
		agrees = d.agrees / Math.max(d.disagrees, 1) > 1.5;
	}

	var disagrees = true;
	if (yd_settings.ui.filters.disagrees) {
		disagrees = d.disagrees / Math.max(d.agrees, 1) > 1.5;
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

	return language && recent && popular && agrees && disagrees && history;
}

/**
 * Loads the narrative player once the popup page with audio is ready.
 */
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
			if (jQuery(this).hasClass('autoplay')) {
				myaudio.play();
			}
		}, false);
		// Update as the audio continues to play.
		myaudio.addEventListener('timeupdate', function(e) {
			if (e.timeStamp - player_last_update > yd_settings.constants.NARRATIVE_PLAYER_IMAGE_UPDATE_INTERVAL) {
				player_last_update = e.timeStamp;
				narrative_player_update_image(myaudio.currentTime);
			}
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
		var url = yd_settings.site_url + "comments/reply/" + narrative_id;
		var formdata = jQuery("#new-comment-form").serialize();
		jQuery.post(url, formdata)
			.done(function(data) {
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
	// Click handler: Comment (on comment)
	jQuery(".action-comment-reply").not('.comment-processed').addClass('comment-processed').click(function() {
		var narrative_id = jQuery('#new-comment-form input[name=narrative_id]').val();
		var parent_comment_id = jQuery(this).parents('.comment').attr('id').substring(8);
		var url = yd_settings.site_url + "comments/reply/" + narrative_id + '/' + parent_comment_id;
		var formdata = jQuery("#new-comment-form").serialize();
		jQuery.post(url, formdata)
			.done(function(data) {
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
	// Click handler: Flag (on comment)
	jQuery(".action-comment-report").not('.comment-processed').addClass('comment-processed').click(function() {
		var comment_id = jQuery(this).parents('.comment').attr('id').substring(8);
		var url = yd_settings.site_url + "comments/flag/" + comment_id;
		var formdata = jQuery("#new-comment-form").serialize();
		jQuery.post(url, formdata)
			.done(function(data) {
				jQuery("#new-comment").val('');
				jQuery('#comment-' + comment_id + ' .action-comment-report').css('color', 'red');
				alert("Thank you, this comment has been reported.");
			})
			.fail(function() {
				alert("An error occurred while reporting the comment. Please try again.");
			});
	});
}

/**
 * Binds callbacks to the narrative player's buttons (voting, sharing, etc).
 */
function narrative_player_buttons_initialize()
{
	//Handle flagging of narrative
	jQuery(".action-narrative-report").click(function() {
		var url = yd_settings.site_url + "player/flag/" + nar_id;
		jQuery.post(url)
			.done(function() {
				alert("Thank you, narrative has been reported.");
			})
			.fail(function() {
				alert("An error occurred while reporting the narrative. Please try again.")
			});
	});

	//Handle bookmarking of narrative
	jQuery(".bookmark-btn").click(function() {
		  window.location.assign(yd_settings.site_url + "narratives/" + nar_id);
	});

	//local var to decide agree/disagree
	var last_concensus = "";
	//get narrative ID
	var nar_id = jQuery(".page-header small").text();
	//If agree or disagree button is pressed
	jQuery(".player-buttons .float-right .btn-group .btn").click(function() {

		//Increment the agrees, decrement the disagrees
		if(last_concensus == "Agree" && jQuery.trim(jQuery(this).text()) == "Disagree")
		{
			var url = yd_settings.site_url + "ajax/toggle_agree_to_disagree/" + nar_id;
			//set the last user choice to disagree
			last_concensus = jQuery.trim(jQuery(this).text());
			jQuery(this).toggleClass('active btn-primary');
			jQuery('.player-buttons .float-right .btn-group .btn').not(this).removeClass('active btn-primary');
			jQuery.post(url)
			.done(function(data) {
				jQuery(".player-stats .float-right .red.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())) + 1 + " ");
				jQuery(".player-stats .float-right .green.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())) - 1 + " ");
				update_concensus_bar(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())), parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())));
			})
			.fail(function() {
				alert("An error occurred while voting.");
			});
		}
		//Increment the disagrees, decrement the agrees
		else if(last_concensus == "Disagree" && jQuery.trim(jQuery(this).text()) == "Agree")
		{
			var url = yd_settings.site_url + "ajax/toggle_disagree_to_agree/" + nar_id;
			//set the last user choice to agree
			last_concensus = jQuery.trim(jQuery(this).text());
			jQuery(this).toggleClass('active btn-primary');
			jQuery('.player-buttons .float-right .btn-group .btn').not(this).removeClass('active btn-primary');
			jQuery.post(url)
			.done(function(data) {
				jQuery(".player-stats .float-right .green.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())) + 1 + " ");
				jQuery(".player-stats .float-right .red.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())) - 1 + " ");
				update_concensus_bar(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())), parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())));
			})
			.fail(function() {
				alert("An error occurred while voting.");
			});
		}
		//Increment the disagrees or agrees
		else if(last_concensus == "")
		{
			//set last_concensus according to the button pressed (agree/disagree)
			last_concensus = jQuery.trim(jQuery(this).text());
			var url = yd_settings.site_url + "ajax/increment_agrees_disagrees/" + nar_id + "/" + last_concensus;
			jQuery(this).toggleClass('active btn-primary');
			jQuery('.player-buttons .float-right .btn-group .btn').not(this).removeClass('active btn-primary');
			jQuery.post(url)
			.done(function(data) {
				if(last_concensus == "Agree")
				{
					jQuery(".player-stats .float-right .green.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())) + 1 + " ");
				}
				else if(last_concensus == "Disagree")
				{
					jQuery(".player-stats .float-right .red.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())) + 1 + " ");
				}
				//fade-in and fade-out a message
				//fade_in_success_message("Your vote has been accepted");
				//setTimeout(fade_out_success_message, 2000);
				update_concensus_bar(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())), parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())));
			})
			.fail(function() {
				alert("An error occurred while voting.");
			});
		}
		else if( last_concensus == jQuery.trim(jQuery(this).text()) )
		{
			var url = yd_settings.site_url + "ajax/decrement_agrees_disagrees/" + nar_id + "/" + last_concensus;
			//set last_concensus to an empty string.
			jQuery(this).removeClass('active btn-primary');
			
			$.post(url)
			.done(function(data) {
				if(last_concensus == "Agree")
				{
					jQuery(".player-stats .float-right .green.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())) - 1 + " ");
				}
				else if(last_concensus == "Disagree")
				{
					jQuery(".player-stats .float-right .red.text").text(parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())) - 1 + " ");
				}
				//fade-in and fade-out a message
				//fade_in_success_message("Your vote has been accepted");
				//setTimeout(fade_out_success_message, 2000);
				last_concensus = "";
				update_concensus_bar(parseInt(jQuery.trim(jQuery(".player-stats .float-right .green.text").text())), parseInt(jQuery.trim(jQuery(".player-stats .float-right .red.text").text())));
			})
			.fail(function() {
				alert("An error occurred while voting.");
			});
		}
	});

	function update_concensus_bar(agrees, disagrees)
	{
		var total_votes = agrees + disagrees;
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
