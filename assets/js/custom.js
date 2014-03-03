String.prototype.repeat = function(num) {
	return new Array(num + 1).join(this);
}

jQuery(document).ready(function() {
	yd_settings.ui = {
		transition_duration: 1500,
		ring_inner_radius: 0.8,
		filtered_opacity: 0.2
	}
	// Add colorbox to any items with a "colorbox" class
	initialize_color_box();

	// This will do nothing on most pages, but prepare any audio embeds we have
	// present on page load (i.e. on admin review narrative page)
	narrative_player_load();

	// Toggle buttons for navigation links
	jQuery('.filter-container.btn-group a').click(function() {
		jQuery(this).toggleClass('active');
		jQuery('.filter-container.btn-group a').not(this).removeClass('active');
		yd_settings.language_filter = jQuery('.filter-container.btn-group a.active').attr('href');
		return false;
	});

	if (jQuery('#bubble-container').not('.bubbles-processed').addClass('bubbles-processed').length) {
		yd_settings.sort_by = jQuery('.sort-container.btn-group a.active').attr('href').substring(1);
		reload_bubbles();
	}

	// Radio-like toggle buttons for sort
	jQuery('.sort-container.btn-group a').click(function () {
		jQuery(this).toggleClass('active');
		yd_settings.recent_filter = jQuery('.sort-container.btn-group a[href="#age"]').hasClass('active');
		return false;
	});
});

function initialize_color_box() {
	jQuery('a, area, input')
    .filter('.colorbox:not(.initColorbox-processed)')
    .addClass('initColorbox-processed')
    .colorbox();
}

function reload_bubbles() {
	jQuery('.sort-container.btn-group a').unbind('click');
	jQuery('.svg-container').html('');

	loadBubbles(null, yd_settings.constants.NARRATIVE_POSITION_NEUTRAL);
	loadBubbles(null, yd_settings.constants.NARRATIVE_POSITION_AGREE);
	loadBubbles(null, yd_settings.constants.NARRATIVE_POSITION_DISAGREE);
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
	var width = diameter/num_parent_bubbles-4;
	var height = diameter/num_parent_bubbles;
	var format = d3.format(",.0f");
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

		var circles = vis.append("circle")
			.attr("r", function(d) { return d.r; })
			.attr("id", function(d) { return 'narrative-' + d.narrative_id; })
			.attr("class", function(d) { return !d.children ? 'node-base' : 'node-parent'; })
			.style("fill", bubble_fill_color)
			.style("opacity", function(d) { return !d.children ?  0.5: 1; })
			.style("cursor", function(d) { return d.children ?  "normal" : "pointer"; });

		var positionLabel = svg.append('text')
				.attr("dx", 230)
				.attr("dy", 25)
				.style("text-anchor", "middle")
				.style("font-size", "2em")
				.text(position_label_text(position));

		// This computes the SVG path data required to form an arc.
		var arc = d3.svg.arc()
			.outerRadius(function(d) { return d.r; })
			.innerRadius(function(d) { return d.r*yd_settings.ui.ring_inner_radius; });

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
		var arcs = vis.selectAll("g.slice")
			.data(radiusmapper)
			.enter()
			.append("svg:g")
			.attr("class", "slice")

		// In the container, write a path based on the generated arc data
		var paths = arcs.append("svg:path")
			.attr("fill", function(d, i) { return d.data.label == 'agrees' ? bubble_colors.green : bubble_colors.red; } )
			.attr("d", arc);

		// This comes after the paths so that the text doesn't get covered by the
		// path rendering
		var nodes = vis.filter(function(d, i) { return !d.children && d.r > 35 }).append("text")
			.attr('dy', '-0.6em')
			.style("text-anchor", "middle")
			.style("cursor", "pointer");
		nodes.append('svg:tspan')
			.text(function(d) { return d.agrees; })
			.style("dominant-baseline", "central")
		nodes.append('svg:tspan')
			.text(glyphicon_map.agrees)
			.attr('dx', '0.3em')
			.style("dominant-baseline", "central")
			.style('font-family', "'Glyphicons Halflings")
			.style('fill', bubble_colors.green);

		var nodes2 = vis.filter(function(d, i) { return !d.children && d.r > 35 }).append("text")
			.attr('dy', '0.6em')
			.style("text-anchor", "middle")
			.style("cursor", "pointer")
			.style("display", function(d) {console.log(d); return null});
		nodes2.append('svg:tspan')
			.text(function(d) { return d.disagrees; })
			.style("dominant-baseline", "central")
		nodes2.append('svg:tspan')
			.text(glyphicon_map.disagrees)
			.attr('dx', '0.3em')
			.style("dominant-baseline", "central")
			.style('font-family', "'Glyphicons Halflings")
			.style('fill', bubble_colors.red);

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

		// Maps initial data to bubble pack
		updateVis(svgselect);

		/**
		 * Binds actual data to the DOM and provides a transition if a new ordering
		 * is preferred by user.
		 */
		function updateVis(svgselect) {
			console.log('updating bubbles to be sorted by ' + yd_settings.sort_by);
			//to update pack values, use: pack.value(function() { return metric; });
			//to update data, use: pack.nodes(data)

			vis.transition().duration(yd_settings.ui.transition_duration)
				.attr("transform", function(d) { return 'translate(' + d.x +',' + d.y + ')'; })
				.style("opacity", function(d) { return narrative_matches_filter(d) ? 1 : yd_settings.ui.filtered_opacity; });

			titles.attr("x", function(d) { return 0; })
				.attr("y", function(d) { return 0; })
				.text(function(d) { return (d.children ? d.name : 'Narrative ' + d['narrative_id'] + ": " + format(d.value)); });

			circles.transition().duration(yd_settings.ui.transition_duration)
					.style("fill", bubble_fill_color)
					.attr("r", function(d) { return d.r; });

			arcs.data(radiusmapper)

			paths.data(radiusmapper)
			paths.transition().duration(yd_settings.ui.transition_duration)
				.attr("d", function(d) { return arc(d); });

			// Ring hover
			jQuery(svgselect + ' g.node-base').hover(
				function() { if (narrative_matches_filter(this.__data__)) { jQuery('circle', this).css('opacity', 0.7); }},
				function() { jQuery('circle', this).css('opacity', 0.5); }
			);
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

function date_from_string(str) {
	var a = $.map(str.split(/[^0-9]/), function(s) { return parseInt(s, 10); });
	return new Date(a[0], a[1]-1 || 0, a[2] || 1, a[3] || 0, a[4] || 0, a[5] || 0, a[6] || 0);
}

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

bubble_fill_color = function(d) {
	if (d.children) {
		return bubble_colors.lightgrey;
	}
	switch (parseInt(d.position)) {
		case yd_settings.constants.NARRATIVE_POSITION_NEUTRAL:
			return bubble_colors.darkgrey;

		case yd_settings.constants.NARRATIVE_POSITION_AGREE:
			return bubble_colors.purple;

		case yd_settings.constants.NARRATIVE_POSITION_DISAGREE:
			return bubble_colors.blue;

		default:
		  return bubble_colors.lightgrey;
	}
}

function narrative_matches_filter(d) {
	var recent = true;
	if (!d.children && yd_settings.recent_filter) {
		var today = new Date();
		today.setDate(today.getDate() - 7);
		var dateStr = today.getFullYear() + '-' + (today.getMonth()+1) + '-' + today.getDate() + ' ' + today.getHours() + ':' + today.getMinutes() + ':' + today.getSeconds();
		recent = date_from_string(d.uploaded) > date_from_string(dateStr);
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
	var narrative_id = player.attr('id').substring(10);
	var url = yd_settings.site_url + "ajax/audio_image/" + narrative_id + "/" + timecode;
	jQuery.get(url, function(data) {
		jQuery("#audio_image").attr('src', data);
	});
}

function initialize_commenting() {
	// Click handler: Comment (root level)
	jQuery(".action-comment-post").not('.comment-processed').addClass('comment-processed').click(function() {
		var narrative_id = jQuery('#new-comment-form input[name=narrative_id]').val();
		var url = yd_settings.site_url + "comments/reply/" + narrative_id;
		var formdata = jQuery("#new-comment-form").serialize();
		$.post(url, formdata)
			.success(function(data) {
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
		$.post(url, formdata)
			.success(function(data) {
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
		$.post(url, formdata)
			.success(function(data) {
				jQuery("#new-comment").val('');
				alert("Thank you, this comment has been reported.");
			})
			.fail(function() {
				alert("An error occurred while reporting the comment. Please try again.");
			});
	});
}
