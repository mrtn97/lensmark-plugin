=== Lensmark ===
Contributors: martinclement
Tags: citizen science, photo-monitoring
Requires at least: 6.1.1
Tested up to: 6.2
Requires PHP: 7.4.33
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

A citizen-science photo-monitoring solution for WordPress CMS.

== Disclaimer ==
This is a first prototype for demonstration purposes only and is not suitable for production sites. Interested in joining the project? Contact us at https://lensmark.org/contact.

== Description ==
This plugin was developed as part of a bachelor thesis. The Förderverein Region Gantrisch commissioned the Bern University of Applied Sciences to develop a prototype of a photo-monitoring platform.

This plugin was built with the WordPress Plugin Boilerplate (https://github.com/DevinVinson/WordPress-Plugin-Boilerplate For more information about the project, please visit https://lensmark.org.

The logo was made by Lara Baeriswyl (http://larabaeri.ch/)

== Installation ==
1. Install and activate the plugin.
2. Setup one or several photoposts.
3. Edit the map overview settings on the plugin settings page.
4. Add shortcodes in your Editor. [lensmark-map-overview], [lensmark-submission-form], [lensmark-timelapse], [lensmark-photopost-details].

== Frequently Asked Questions ==
= Is there a documentation about the plugin? =
Yes, https://lensmark.org/documentation
= Can I use this plugin for my project? =
No, it is an MVP (minimal viable product) solution. This plugin is for demonstration purposes only.
= Can I participate in this? =
Yes, feel free to reach out to martin.clement@outlook.com.

== Changelog ==
= 1.0.0 =
* Initial version of the plugin (MVP for demonstration purposes only).
* Photopost Post-type (pageslug: /photopost/)
* Interactive map (leaflet.js) (shortcode: [lensmark-map-overview])
* Submission form (shortcode: [lensmark-submission-form])
* Timelapse (shortcode: [lensmark-timelapse])
* Photopost details including Position (latitude, longitude), location name and activation date (shortcode: [lensmark-photopost-details])