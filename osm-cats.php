<?php
/*
Plugin Name: OSM Categories
Plugin URI: https://github.com/KiTo/OSM-Categories
Description: OpenStreetMap plugin to embed a map with markers to articles from different categories on different layers.
Version: 1.0
Author: Guido Handrick
Author http://guido-handrick.info
License: GPL2
*/

/*  Copyright 2012  Guido Handrick  (email : ghandrick@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_action( 'admin_menu', 'osm_cats_menu' );
add_action( 'admin_init', 'register_osm_cats_settings' );

function register_osm_cats_settings() {
  register_setting( 'osm_cats', 'osm_cats_map_width' );
  register_setting( 'osm_cats', 'osm_cats_map_height' );
  register_setting( 'osm_cats', 'osm_cats_center_lon' );
  register_setting( 'osm_cats', 'osm_cats_center_lat' );
  register_setting( 'osm_cats', 'osm_cats_zoom_level' );
  register_setting( 'osm_cats', 'osm_cats_exclude_cats' );
  register_setting( 'osm_cats', 'osm_cats_marker_custom_field' );
  register_setting( 'osm_cats', 'osm_cats_marker_show_thumbnail' );
  register_setting( 'osm_cats', 'osm_cats_marker_show_excerpt' );
  register_setting( 'osm_cats', 'osm_cats_marker_images_path' );
}

function osm_cats_menu() {
	add_options_page( 'OSM Categories Plugin Options', 'OSM Categories', 'manage_options', 'osm_cats_plugin', 'osm_cats_plugin_options' );
}

function osm_cats_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap">
  <h2>OSM Categories Settings</h2>

  <form method="post" action="options.php">
    <?php settings_fields( 'osm_cats' ); ?>
    <h3>General OSM-map settings</h3>
    <table class="form-table">

      <tr valign="top">
        <th scope="row">OSM map width</th>
        <td>
          <input type="text" name="osm_cats_map_width" value="<?php echo get_option('osm_cats_map_width'); ?>" />
          <small>Default is 100%, don't forget the unit.</small>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">OSM map height</th>
        <td>
          <input type="text" name="osm_cats_map_height" value="<?php echo get_option('osm_cats_map_height'); ?>" />
          <small>Default is 300px, don't forget the unit.</small>
        </td>
      </tr>
      
      <tr valign="top">
        <th scope="row">Zoom Level</th>
        <td>
          <input type="text" name="osm_cats_zoom_level" value="<?php echo get_option('osm_cats_zoom_level'); ?>" />
          <small>Default is 12, the OSM values range is from 0 to 18.</small>
        </td>
      </tr>
      
      <tr valign="top">
        <th scope="row">Center Lon</th>
        <td><input type="text" name="osm_cats_center_lon" value="<?php echo get_option('osm_cats_center_lon'); ?>" /></td>
      </tr>
      
      <tr valign="top">
        <th scope="row">Center Lat</th>
        <td><input type="text" name="osm_cats_center_lat" value="<?php echo get_option('osm_cats_center_lat'); ?>" /></td>
      </tr>
    </table>
    <h3>Category settings</h3>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Exclude categories</th>
        <td>
          <input type="text" name="osm_cats_exclude_cats" value="<?php echo get_option('osm_cats_exclude_cats'); ?>" />
          <small>A comma seperatet list of category ID's.</small>
        </td>
      </tr>
    </table>
    <h3>Marker settings</h3>
    <table class="form-table">  
      <tr valign="top">
        <th scope="row">LonLat Custom Field</th>
        <td>
          <input type="text" name="osm_cats_marker_custom_field" value="<?php echo get_option('osm_cats_marker_custom_field'); ?>" />
          <small>Name of the custom field for LonLat parameter of an article. Default is LonLat.</small>
        </td>
      </tr>
      
      <th scope="row">Marker popup settings</th>
        <td>
          <input type="checkbox" name="osm_cats_marker_show_thumbnail" id="osm_cats_marker_show_thumbnail" value="1" <?php checked( '1', get_option( 'osm_cats_marker_show_thumbnail' ) ); ?> />
          <label for="osm_cats_marker_show_thumbnail">Show article thumbnail</label><br />
          <input type="checkbox" name="osm_cats_marker_show_excerpt" id="osm_cats_marker_show_excerpt" value="1" <?php checked( '1', get_option( 'osm_cats_marker_show_excerpt' ) ); ?> />
          <label for="osm_cats_marker_show_excerpt">Show article excerpt</label>
        </td>
      </tr>
    </table>  
    
    <h4>How to create your own marker images</h4>
    <ol>
      <li>Create your own marker images for each category, ore one for all.</li>
      <li>Name your images: marker_CATEGORY-ID.png or just marker.png</li>
      <li>Create a folder on your webserver, for example: /wp-content/osm-marker</li>
      <li>Copy your images to this folder.</li>
      <li>Enter the folder path below.</li>
    </ol>
    <p>If you don't create your own images, the default OSM marker image will be used.</p>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Marker images path</th>
        <td>
          <input type="text" name="osm_cats_marker_images_path" value="<?php echo get_option('osm_cats_marker_images_path'); ?>" />
          <small>The absolute path to your marker images folder. If the path is correct you can see all your marker images below after saving.</small>
          <?php
          if ($handle = opendir($_SERVER['DOCUMENT_ROOT'].get_option('osm_cats_marker_images_path'))) {
            echo '<p>';
            while (false !== ($entry = readdir($handle))) {
              if ($entry != "." && $entry != "..") {
                if ($entry == 'marker.png') {
                  echo "<img src='".get_option('osm_cats_marker_images_path')."/".$entry."' alt='$entry' /> Marker for all categories.<br />";
                } else {
                  echo "<img src='".get_option('osm_cats_marker_images_path')."/".$entry."' alt='$entry' /> Marker for category with ID ".str_replace('marker_','',str_replace('.png','',$entry)).".<br />";
                }
              }
            }
            echo '</p>';
            closedir($handle);
          }
          ?>
        </td>
      </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

  </form>
  </div>
  <?php
}

function osm_cats_code( $atts ){
  //
  $message = '';

  // get option for map width, if not set switch to 100% by default
  $map_width_check = get_option('osm_cats_map_width');
  $map_width = ($map_width_check)?$map_width_check:'100%';

  // get option for map height, if not set switch to 300px by default
  $map_height_check = get_option('osm_cats_map_height');
  $map_height = ($map_height_check)?$map_height_check:'300px';

  // get option for map center
  $map_center = get_option('osm_cats_center_lon').','.get_option('osm_cats_center_lat');

  // get option for map zoom level, if not set switch to 12 by default
  $zoom_level_check = get_option('osm_cats_zoom_level');
  $zoom_level = ($zoom_level_check)?$zoom_level_check:12;

  // get exluded categories
  $exclude_cats = get_option('osm_cats_exclude_cats');

  // get option for article lonlat custom field, if not set switch to LonLat by default
  $lonlat_custom_field_check = get_option('osm_cats_marker_custom_field');
  $lonlat_custom_field = ($lonlat_custom_field_check)?$lonlat_custom_field_check:'LonLat';
  
  // get options for marker popup
  $show_thumbnail = get_option('osm_cats_marker_show_thumbnail');
  $show_excerpt = get_option('osm_cats_marker_show_excerpt');
  
  // get option for marker image path
  $marker_image_path = get_option('osm_cats_marker_images_path');
  
  // if no center is defined in the settings set center to 0,0 and zoom to 0, echo info message
  if($map_center == ',') {
    $map_center = '0,0';
    $zoom_level = 0;
    $message = 'Please define the center of your map on the <a href="/wp-admin/options-general.php?page=osm_cats_plugin">plugin settings page</a>.';
  }

  // get categories for map layers
  $args = array(
    'exclude' => $exclude_cats,
  );
  $categories=get_categories($args);
  
  // get posts for markers
  $args = array(
    'posts_per_page' => -1,
    'category__not_in' => explode(',',$exclude_cats)
    );
  query_posts($args);
  
  // the markup starts here
  ?>
  <style>
    #mapdiv img { max-width: none; }
  </style>
  <div id="mapdiv" style="height: <?php echo $map_height; ?>; width: <?php echo $map_width; ?>;"></div>
  <?php echo ($message)?"<p>$message</p>":""; ?>
  <script src="http://www.openlayers.org/api/OpenLayers.js"></script>
  <script>
    var map;
    var layer, markers, size, offset, icon;
    var currentPopup;
    var zoom;
    var center
    
    // marker popup style
    AutoSizeAnchored = OpenLayers.Class(OpenLayers.Popup.Anchored, {
      'autoSize': true
    });
    
    // inital function for the osm map
    function init(){
      map = new OpenLayers.Map('mapdiv');
      map.addLayer(new OpenLayers.Layer.OSM());       
                 
      <?php
      // create a layer for every category 
      foreach($categories as $category) {
        echo "markers_$category->cat_ID = new OpenLayers.Layer.Markers('$category->cat_name');";
        echo "map.addLayer(markers_$category->cat_ID);";
      }
      ?>

      map.addControl(new OpenLayers.Control.LayerSwitcher());
      
      // set zoom
      zoom = <?php echo $zoom_level; ?>;
      // center map
      center = new OpenLayers.LonLat( <?php echo $map_center; ?> )
              .transform(
                new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
                map.getProjectionObject() // to Spherical Mercator Projection
            );
      map.setCenter (center, zoom);

      addMarkers();
    }
    
    function addMarkers() {
      var ll, layer, popupContentHTML;
      
      <?php
      // add a marker for every post
      if (have_posts()) {
        while (have_posts()) {
          $custom_icon = false;
          the_post();
          $lonlat_value = get_post_meta(get_the_ID(), $lonlat_custom_field, true);
          if($lonlat_value != '') {
            echo "ll = new OpenLayers.LonLat( $lonlat_value ).transform(new OpenLayers.Projection('EPSG:4326'), map.getProjectionObject());";
            
            $show_thumbnail_markup = ($show_thumbnail)?"<a href=\'".get_permalink()."\'>".get_the_post_thumbnail(get_the_ID(),'thumbnail')."</a>":"";
            $show_title_markup = "<a href=\'".get_permalink()."\'><h3>".get_the_title()."</h3></a>";
            $show_excerpt_markup = ($show_excerpt)?"<p><small>".get_the_excerpt()."</small></p>":"";
            
            echo "popupContentHTML = '".$show_thumbnail_markup.$show_title_markup.$show_excerpt_markup."';";
            
            $category = get_the_category();
            
            echo "layer = markers_".$category[0]->term_id.";";
            
            $image_path = $_SERVER['DOCUMENT_ROOT'].$marker_image_path."/marker_".$category[0]->term_id.".png";
            if(file_exists($image_path)) {
              list($width, $height)= getimagesize($image_path); 
              echo "size = new OpenLayers.Size(".$width.",".$height.");";
              echo "offset = new OpenLayers.Pixel(-(size.w/2), -size.h);";
              echo "icon = new OpenLayers.Icon('/wp-content/osm-marker/marker_".$category[0]->term_id.".png', size, offset);"; 
              $custom_icon = true;
            } else {
              $image_path = $_SERVER['DOCUMENT_ROOT'].$marker_image_path."/marker.png";
              if(file_exists($image_path)) {
                list($width, $height)= getimagesize($image_path); 
                echo "size = new OpenLayers.Size(".$width.",".$height.");";
                echo "offset = new OpenLayers.Pixel(-(size.w/2), -size.h);";
                echo "icon = new OpenLayers.Icon('/wp-content/osm-marker/marker.png', size, offset);"; 
                $custom_icon = true;
              }
            }
            echo ($custom_icon)?"addMarker(ll, popupContentHTML, layer, icon);":"addMarker(ll, popupContentHTML, layer, false);";
          }
        }
      }
      ?>
    }
    
    function addMarker(ll, popupContentHTML, layer, icon) {
      
      // create marker and popup
      var feature = new OpenLayers.Feature(layer, ll); 
      feature.closeBox = true;
      feature.popupClass = AutoSizeAnchored;
      feature.data.popupContentHTML = popupContentHTML;
      feature.data.overflow = "auto";
      if (icon) feature.data.icon = icon;
      
      var marker = feature.createMarker();
      
      var markerClick = function (evt) {
          if (this.popup == null) {
              this.popup = this.createPopup(this.closeBox);
              map.addPopup(this.popup);
              this.popup.show();
          } else {
              this.popup.toggle();
          }
          currentPopup = this.popup;
          OpenLayers.Event.stop(evt);
      };
      
      marker.events.register("mousedown", feature, markerClick); 

      layer.addMarker(marker);
      
    }
    
    // init call
    init();
  </script>
  <?php
  wp_reset_query();
}

add_shortcode( 'osm_cats', 'osm_cats_code' );
?>
