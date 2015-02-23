<?php

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

// Convert links to bitly links

function get_bitly_short_url($url,$login,$appkey,$format='txt') {
	$connectURL = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&format='.$format;
	return curl_get_result($connectURL);
}

function curl_get_result($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

$url = "http://www.easyrider.nu/txtly";
$login = "maddeeasyrider";
$appkey = "R_a835527cec7344eea3ca1de2dd2879cc";
$format= "txt";

function shortUrl($m) {
		$login = "maddeeasyrider";
		$appkey = "R_a835527cec7344eea3ca1de2dd2879cc";
        static $id = 0;
        $id++;
        return get_bitly_short_url($m[1],$login,$appkey);
}

$short_url = get_bitly_short_url($url,$login,$appkey);
