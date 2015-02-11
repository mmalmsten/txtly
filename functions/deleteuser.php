<?php

function DeleteUserImages($deleteThisUser, $directory) {
	$showDir = "../img/".$directory;
	$dir = scandir("$showDir");
	foreach ($dir as $key => $img){
		if (strpos($img, $deleteThisUser) !== false) {
		  unlink($showDir."/".$img);
		}
	}
}

if (isset($_POST["drop"])){
	$deleteThisUser = $_POST["drop"];

	include 'link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();

	$sql="DELETE FROM users WHERE user='$deleteThisUser'";

	if (!mysqli_query($link,$sql)) {
		print "Sorry, something went wrong!<br>";
		die('Error: ' . mysqli_error($link));
	}

	DeleteUserImages($deleteThisUser, "profileimg");
	DeleteUserImages($deleteThisUser, "headerimg");

	mysqli_close($link);
}