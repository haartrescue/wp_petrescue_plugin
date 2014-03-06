<?php
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
?>