OSM Categories
==============
Contributors: Guido Handrick
Tags: OpenStreetMap, geotag, geolocation, geocache, geocaching, OSM, travelogue, travelblog, OpenLayers, Open Layers, Open Street Map, marker, geocode, geotagging
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: 1.0 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

OpenStreetMap plugin to embed a map with markers to articles from different categories in different map layers. 


Description
-------------

OSM Categories embed an OpenStreetMap map to your page by using the OpenLayer API. For every category in your blog a differnt layer on your map show markers for every article with an geotag.
You just have to save the lon and lan parameters in a custom field. It's possible to use different marker images for every category.

In your page just insert the shortcode: [osm-cats] 

Open the plugin settings page for basic settings like:

- map dimensions
- map center point
- initial zoom faktor
- exclude categories
- article custom field for marker lon and lat parameters
- marker popup content
- marker images path

It's still BETA so please send me feedback and your ideas! Thanx a lot.

Frequently Asked Questions
-------------

= Why i see the hole world on the map? =

It's necessary to define a map center point on the plugin settings page.

= Where can i give you feedback? =

I sink the best would be here: https://github.com/KiTo/OSM-Categories

Installation 
-------------

Either:

1. Search for and install OSM Categories directly through the 'Plugins' menu in WordPress

Or:

1. Download and unzip OSM Categories
1. Upload the `osm-categories` directory to the `/wp-content/plugins` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Changelog 
-------------

1.0
* NEW: Include or exclude categories
* NEW: Add Google Baselayers - Terrain, Roadmap, Satellite, Hybrid
* NEW: Show only images with marker in the filename on the settings page

0.2
* FIX: Use marker path from settings
* NEW: Possibility to disable zoom wheel

0.1
* NEW: First release with basic features
