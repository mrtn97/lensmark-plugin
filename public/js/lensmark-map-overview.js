/**
 * Javascript file handling the photoposts-map-overview displaying all photoposts.
 * 
 * @since      1.0.0
 * 
 */

(function ($) {
  "use strict";

  $(window).load(function () {
// Search for DIV element with id=map, set view coordinates and zoom-level
    var map = L.map("map").setView([46.725, 7.4528], 12);

	  // Render map layer
    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 19,
      attribution:
        '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    }).addTo(map);
		// Send AJAX request to retrieve photoposts sent from class-lensmark-public.php:lensmark_get_photoposts()
		jQuery.ajax({
			url: lensmark_ajax.ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'lensmark_get_photoposts'
			},
			success: function(response) {
				// Loop through the response and add markers to the map
				jQuery.each(response, function(index, post) {
					var lat = post.latitude;
					var lng = post.longitude;
					if (lat && lng) {
						var marker = L.marker([lat, lng]);
						marker.addTo(map);
						marker.bindPopup('<img src="' + post.thumbnail + '" alt="" width="100%" height="100"><br>' + '</b><br>'  + '<h3>' + post.title + '</h3>' + 'Photopost: ID:' + post.id + '</b><br>' + 'Position:' + post.latitude + ' | ' + post.latitude + '</b><br>' + post.excerpt + '</b><br>' + '<a href="' + post.permalink + '">Open</a>');
					}
				});
			}
  });

	jQuery(document).ready(function($) {
    
		});
	});
})(jQuery);