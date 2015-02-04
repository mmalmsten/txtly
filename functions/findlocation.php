<?php

// lat and lng to area, city and country
if (isset($lng)) {

	$address = "$lat,$lng";

	$request = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . $address . "&sensor=false");
	$json = json_decode($request, true);

	$city = $json['results'][0]['address_components'][2]['long_name'].", ".$json['results'][0]['address_components'][4]['long_name'];
	$country = $json['results'][0]['address_components'][6]['long_name'];

}

?>