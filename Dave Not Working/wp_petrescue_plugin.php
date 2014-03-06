<?php
/*
Plugin Name: PetRescue API Wordpress Plugin
Plugin URI: http://www.haart.org.au/plugins/petrescue
Description: Creates the "Find a Pet" functionality that uses PetRescue (AU) as a base for all animals.
Version: 1.0
Author: Dave Forster
Author URI: http://www.haart.org.au
License: GPL2
*/
/*
Copyright 2014  Dave Forster  (email : dave@haart.org.au)

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

if(!class_exists('WP_PetRescue_Plugin'))
{
	class WP_PetRescue_Plugin
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$WP_PetRescue_Plugin_Settings = new WP_PetRescue_Plugin_Settings();

			$plugin = plugin_basename(__FILE__);
			add_filter("plugin_action_links_$plugin", array( $this, 'plugin_settings_link' ));
		} // END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate

		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=WP_PetRescue_Plugin">PetRescue Settings</a>';
			array_unshift($links, $settings_link);
			return $links;
		}


	} // END class WP_PetRescue_Plugin
} // END if(!class_exists('WP_PetRescue_Plugin'))

if(class_exists('WP_PetRescue_Plugin'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('WP_PetRescue_Plugin', 'activate'));
	register_deactivation_hook(__FILE__, array('WP_PetRescue_Plugin', 'deactivate'));

	// instantiate the plugin class
	$wp_petrescue_plugin = new WP_PetRescue_Plugin();

}

add_action('init', 'find_a_pet');
 
function find_a_pet() {
 
$ch = curl_init();
$category=$_GET['category'];
$url="http://www.petrescue.com.au/api/listings?token=f716909f5d644fe3702be5c7895aa34e&amp;group_id=10046&amp;species=".$category;
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Accept: application/json',
'X-some-API-Key: f716909f5d644fe3702be5c7895aa34e',
));

// grab URL and pass it to the browser
//echo curl_exec($ch);
$json = json_decode(curl_exec($ch), true);
//echo '[pre]';print_r($json['listings']);echo '[/pre]';
// close cURL resource, and free up system resources
echo '<ul class="pet_listing">';
foreach($json['listings'] as $listing)
{
$short_personality=substr($listing['personality'],0,200);
$peturl="http://www.haart.org.au/pet-info/?petid=".$listing['id'];
$medium_photo=$listing['photos'][0]['medium_130'];
echo '<li class="'.$category.'-listing listing">';
echo '<div class="photo">
<a href="'.$peturl.'">
<img src="'.$medium_photo.'">
</a>
</div>';
echo '<div class="listing_right_side"><h4 class="name">
<a href="'.$peturl.'"]'.$listing['name'].'</a>
</h4>';
echo '<div class="personality">'.$short_personality.'…</div>';

$gender_class=strtolower($listing['gender']);
echo '</div>';
echo '<div class="action_section"><dl class="info-line">
<dt class="gender '.$gender_class.'">Gender</dt>
<dd class="gender '.$gender_class.'">'.$listing['gender'].'</dd>
<dt class="breed">Breed</dt>
<dd class="breed">'.$listing['breeds_display'].'</dd>
</dl>';
echo '<div class="actions">
<span id="find_out_more">
<a href="'.$peturl.'">Find out more</a>
</span>
</div></div>';
echo '</li>';
}
echo '</ul>';
curl_close($ch);
}

add_action('init','pet_info');

function pet_info() {

//print_r($_GET);
$petid=$_GET['petid'];
$ch = curl_init();
$the_url="http://www.petrescue.com.au/api/listings/".$petid."?token=f716909f5d644fe3702be5c7895aa34e";
//echo $the_url;
// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $the_url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Accept: application/json',
'X-some-API-Key: f716909f5d644fe3702be5c7895aa34e',
));

// grab URL and pass it to the browser
//echo curl_exec($ch);
$json = json_decode(curl_exec($ch), true);
//echo '[pre]';print_r($json);echo '[/pre]';
$desexed= ($json['desexed']) ? 'Yes' : 'No';
$photo_featured=$json['photos'][0]['large_340'];
// close cURL resource, and free up system resources
echo '<div id="main">
<h1>'.$json['name'].'</h1>
<div class="pet_details">
<div id="primary">
<article id="listing_content">
<div class="actions" style="display:none;">
<span class="add_remove_favourites">
<form method="post" action="" accept-charset="UTF-8">
</span>
</div>
<div class="share_listing" style="display:none;">
<h5>Share</h5>
<span st_via="PetRescue" st_url="http://www.petrescue.com.au/listings/237141" displaytext="Facebook" class="share_button st_facebook_custom" st_processed="yes">
<span class="icon"></span>
Facebook
</span>
<span st_via="PetRescue" st_url="http://www.petrescue.com.au/listings/237141" displaytext="Twitter" class="share_button st_twitter_custom" st_processed="yes">
<span class="icon"></span>
Twitter
</span>
<span st_via="PetRescue" st_url="http://www.petrescue.com.au/listings/237141" displaytext="Google +" class="share_button st_googleplus_custom" st_processed="yes">
<span class="icon"></span>
Google +
</span>
<span displaytext="Email" data-link-to="/listings/237141/share" class="share_button email_custom">
<span class="icon"></span>
Email
</span>

</div>
<h2 class="species">'.$json['size'].' '.$json['gender'].' '.$json['breeds_display'].'</h2>
<h4 class="located_in">Located in '.$json['state'].'</h4>
<div class="personality">
'.$json['personality'].'
</div>
<h4 class="located_in">Adoption Process</h4>
<div class="adoption_process">'.$json['adoption_process'].'</div>
<h3>'.$json['name'].' Details</h3>
<dl class="pets-details">
<dt class="first age">Age:</dt>
<dd class="first age">'.$json['age'].'</dd>
<dt class="adoption_fee">Adoption Fee</dt>
<dd class="adoption_fee">$'.$json['adoption_fee'].'</dd>
<dt class="desexed">Desexed?</dt>
<dd class="desexed"><span class="boolean-image-true boolean-image-yes">'.$desexed.'</span></dd>
<dt class="vaccinated">Vaccinated?</dt>
<dd class="vaccinated"><span class="boolean-image-true boolean-image-yes">'.$json['vaccinated'].'</span></dd>
<dt class="wormed">Wormed?</dt>
<dd class="wormed"><span class="boolean-image-true boolean-image-yes">'.$json['wormed'].'</span></dd>
<dt class="heart_worm_treated">Heart Worm Treated?</dt>
<dd class="heart_worm_treated"><span class="boolean-image-false boolean-image-no">'.$json['heart_worm_treated'].'</span></dd>
</dl>
</article>
</div>
<div id="secondary">
<div id="pet_images">
<div id="featured_photo">
<a class="lightbox" rel="gallery" href="'.$photo_featured.'">
<img src="'.$photo_featured.'">
</a>
</div>
<ul id="thumbnails">';
$i = 0;
foreach($json["photos"] as $photosthumb)
{
if (++$i > 4) break;
$photo_thumb=$photosthumb["small_80"];
$photo_thumb_large=$photosthumb["large_340"];
echo '<li>
<a class="lightbox" rel="gallery" href="'.$photo_thumb_large.'">
<img src="'.$photo_thumb.'" >
</a>
</li>';
}
echo '</ul>
</div>
</div>
</div></div>';
curl_close($ch);
}

add_action( 'wp_enqueue_scripts', 'wp_petrescue_stylesheet' );
 
function wp_petrescue_stylesheet() {
 
wp_register_style( 'wp-petrescue-style', plugins_url('css/wp_petrescue_style.css', __FILE__), array() );
 
wp_enqueue_style( 'wp-petrescue-style' );
 
}

add_shortcode('petrescue_find_a_pet', 'find_a_pet' );

add_shortcode('petrescue_pet_info', 'pet_info' );