<?php

// Drop row based on ID

if (isset($_POST["drop"])){
	$id = $_POST["drop"];

	include 'link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();

	$sql="DELETE FROM posts WHERE id='$id'";

	if (!mysqli_query($link,$sql)) {
		print "Sorry, something went wrong!<br>";
		die('Error: ' . mysqli_error($link));
	}

	$showDir = "../img/uploads";
	$dir = scandir("$showDir");
	foreach ($dir as $key => $img){
		if (strpos($img, $id) !== false) {
		  unlink($showDir."/".$img);
		}
	}


	mysqli_close($link);
}