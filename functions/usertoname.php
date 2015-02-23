<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

$content = preg_replace_callback('/(@(\w+))/',
			function($userToName) {
				$theUser = $userToName[2];
				include 'link.php';
				$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
				mysqli_connect_errno();
				$userResult = mysqli_query($link, "SELECT * FROM users WHERE user = '$theUser'");

				mysqli_connect_errno();

				if ($userResult) {
					while ($row = mysqli_fetch_assoc($userResult)) {
						return '<a href="profile.php?name='.$theUser.'" class="aName" title="'.$theUser.'">'.$row['name'].'</a>';
					}
					mysqli_free_result($userResult);
				}
				mysqli_close($link);
	        	return '<span class="aName">'.$theUser.'</span>';
	    }, $content);