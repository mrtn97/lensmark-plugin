/**
 * Javascript file handling the photoposts-map-overview displaying all photoposts.
 * 
 * @since      1.0.0
 * 
 */

(function ($) {
	"use strict";

	var map;

	$(window).load(function () {
		jQuery.ajax({
			url: lensmark_ajax.ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'lensmark_get_photoposts'
			},
			success: function(response) {
				// Set map view coordinates
				var map_pos_latitude = response[0].map_pos_latitude;
				var map_pos_longitude = response[0].map_pos_longitude;
				var map_zoom = response[0].map_zoom;
				map = L.map("map", {
					// true by default, false if you want a wild map
					sleep: true,
					// time(ms) for the map to fall asleep upon mouseout
					sleepTime: 750,
					// time(ms) until map wakes on mouseover
					wakeTime: 1000,
					// defines whether or not the user is prompted oh how to wake map
					sleepNote: false,
					// should hovering wake the map? (clicking always will)
					hoverToWake: true,
					// opacity (between 0 and 1) of inactive map
					sleepOpacity: .7
		  
				}).setView([map_pos_latitude, map_pos_longitude], map_zoom);

				L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
				maxZoom: 19,
				attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	  			}).addTo(map);
		
				// Loop through the response and add markers to the map
				jQuery.each(response, function(index, post) {
					var lat = post.latitude;
					var lng = post.longitude;
					if (lat && lng) {
						var marker = L.marker([lat, lng]);
						marker.addTo(map);
						marker.bindPopup(
						'<div class="has-small-font-size">' +
						'<img class="thumbnail" src="' + post.thumbnail_url + '" alt="">'+ 
						'<h3 class="has-large-font-size">' + post.title + '</h3>' +
						'<p><strong>Active since: </strong>' + post.activation_date + '</p>' + 
						'<p><strong>Photopost ID: </strong>' + post.id + '</p>' +
						'<p><strong>Location: </strong>' + post.location + '</p>' +
						'<p><strong>Position: </strong>' + post.latitude + ' | ' + post.latitude +  '</p>' +
						'<p>' + post.excerpt + '</p>' +
						'<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline" ><a href="' + post.link + '" class="wp-block-button__link wp-element-button">Open</a></div>' +
						'</div>'
						);
					}
				});
			}
		});
	});

	$(document).ready(function($) {
		if (map) {
		  map.scrollWheelZoom.disable();
		  map.on('click', function() { 
			map.scrollWheelZoom.enable(); 
			map.off('click'); // remove the click event listener to prevent multiple zooming
		  });
		}
	  });
})(jQuery);