<?php

if (isset($_POST["activeValue"])){
	include 'link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();

	$activeValue = $_POST["activeValue"];

	if (strpos($activeValue,'1') !== false) {
		$value = 0;
	} else{
		$value = 1;
	}

	$user = str_replace("1", "", $activeValue);
	$user = str_replace("0", "", $user);

    $sql=" UPDATE users SET active='$value' WHERE user='$user'";
	
	print "Success $value, $user";

	if (!mysqli_query($link,$sql)) {
		print "Sorry, something went wrong!<br>";
		die('Error: ' . mysqli_error($link));
	}

	mysqli_close($link);
}