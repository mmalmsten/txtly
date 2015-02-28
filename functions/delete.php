<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

function DeleteUserImages($dropThis, $directory) {
	$showDir = "img/".$directory;
	$dir = scandir("$showDir");
	foreach ($dir as $key => $img){
		if (strpos($img, $dropThis) !== false) {
		  unlink($showDir."/".$img);
		}
	}
}

if (isset($_POST["drop"])){
	$dropThis = $_POST["drop"];

	
	$link = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
	mysqli_connect_errno();

	$sql="DELETE FROM users WHERE user='$dropThis'";
	if (!mysqli_query($link,$sql)) {
		print "Sorry, something went wrong!<br>";
		die('Error: ' . mysqli_error($link));
	}

	$sql="DELETE FROM locations WHERE name='$dropThis'";
	if (!mysqli_query($link,$sql)) {
		print "Sorry, something went wrong!<br>";
		die('Error: ' . mysqli_error($link));
	}

	DeleteUserImages($dropThis, "profileimg");
	DeleteUserImages($dropThis, "headerimg");

	mysqli_close($link);
}