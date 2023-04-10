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
						marker.bindPopup('<img src="' + post.thumbnail_url + '" alt="" width="100%" height="100"><br>' + '</b><br>'  + '<h3>' + post.title + '</h3>' + 'Photopost: ID:' + post.id + '</b><br>' + 'Position:' + post.latitude + ' | ' + post.latitude + '</b><br>' + post.excerpt + '</b><br>' + '<a href="' + post.link + '">Open</a>');
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