<?php
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

?>