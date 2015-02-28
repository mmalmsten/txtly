<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

function userInfo($myUser, $showThis){
	
	$link = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
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