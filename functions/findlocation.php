<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

// lat and lng to area, city and country
if (isset($lng)) {

	$address = "$lat,$lng";

	$request = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . $address . "&sensor=false");
	$json = json_decode($request, true);

	$city = $json['results'][0]['address_components'][2]['long_name'].", ".$json['results'][0]['address_components'][4]['long_name'];
	$country = $json['results'][0]['address_components'][6]['long_name'];

} else{
	$city = "";
	$country = "Location unknown";
}

?>