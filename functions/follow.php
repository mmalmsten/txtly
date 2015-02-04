<?php

session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

define("MYUSER", $_SESSION['user']);
define("PAGENAME", $_POST["pagename"]);
define("CURRENT", $_POST["current"]);
define("CURRENTGET", $_POST["currentget"]);

require 'info.php';

function follow($action) {
	include 'link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();

	$someFollowers = "";
	$allFollowers = array();
	$number = 0;

	if (isset($_POST["followuser"]) && $_POST["followuser"] == "follow") {
		$sql="INSERT INTO follow (follower, follows)
		VALUES ('".MYUSER."', '".CURRENT."')"; 

		if (!mysqli_query($link,$sql)) {
		  print "Sorry, something went wrong!<br>";
		  die('Error: ' . mysqli_error($link));
		}
	} 
	else if (isset($_POST["followuser"]) && $_POST["followuser"] == "unfollow") {
		$sql="DELETE FROM follow WHERE follower='".MYUSER."' AND follows='".CURRENT."'";

		if (!mysqli_query($link,$sql)) {
		  print "Sorry, something went wrong!<br>";
		  die('Error: ' . mysqli_error($link));
		}
	}

	if ($action == "Followers") {
		$followers = mysqli_query($link, "SELECT * FROM follow LEFT JOIN users ON user = follower WHERE follows='".MYUSER."' ORDER BY name");	
	} else {
		$followers = mysqli_query($link, "SELECT * FROM follow LEFT JOIN users ON user = follows WHERE follower='".MYUSER."' ORDER BY name");		
	}

	if ($followers) {
		while ($row = mysqli_fetch_assoc($followers)) {
			$number += 1;
			array_push($allFollowers, $row['user']);
			if ($number < 6) {
				$someFollowers .= "<a href='".PAGENAME."?".CURRENTGET."=".$row['user']."'>".$row['name']."</a><br>";
			}
		}
		mysqli_free_result($followers);
	}

	mysqli_close($link); ?>

	<h4 data-toggle="modal" data-target=".<?php print $action ?>modal"><?php print $action ?> <small>(<?php print $number ?>)</small></h4>
	<?php print $someFollowers ?>

	<div class="modal fade <?php print $action ?>modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content modal-follow">
    		<h3><?php print $action ?></h3>
			<table class="table">
		    	<?php foreach ($allFollowers as $user): ?>
					<tr>
						<td><img src="img/profile/<?php print $user ?>.jpg" alt="" class="profilethumbnail"></td>
						<td><?php print UserInfo($user, 'name') ?></td>
						<td>
							<a href="profile.php?name=<?php print UserInfo($user, 'user') ?>">
								@<?php print UserInfo($user, 'user') ?>
							</a>
						</td>
					</tr>
		    	<?php endforeach ?>
			</table>
	    </div>
	  </div>
	</div>

<?php }

follow("Followers");
follow("Follows");

?>
<div class="clear"></div>
