<?php

function userInfo($myUser, $showThis){
	include 'link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();
	$userResult = mysqli_query($link, "SELECT * FROM users WHERE user = '$myUser'");

	mysqli_connect_errno();

	if ($userResult) {
		while ($row = mysqli_fetch_assoc($userResult)) {
			return $row[$showThis];
		}
		mysqli_free_result($userResult);
	}
	mysqli_close($link);
}

function placeInfo($thisPlace, $showThis){
	include 'link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();
	$userResult = mysqli_query($link, "SELECT * FROM locations WHERE name = '$thisPlace'");

	mysqli_connect_errno();

	if ($userResult) {
		while ($row = mysqli_fetch_assoc($userResult)) {
			return $row[$showThis];
		}
		mysqli_free_result($userResult);
	}
	mysqli_close($link);
}