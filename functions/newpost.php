<?php
$newPost = "";
$location = "";
$lat = "";
$lng = "";
$description = "";
$postId = "";
$thisid = "";

if (isset($_POST["newpost"])){
	include 'link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();

	require 'bitly.php';

	$location = "";
	$newPost = $_POST["newpost"];

	// If a new location is submitted
	if (isset($_POST["addnewlocation"]) && isset($_POST["description"]) && $_POST["addnewlocation"] !== "") {
		$location = $_POST["addnewlocation"];
	  	$location = (preg_replace("/[,.<>]/", "", $location));
		$lat = $_POST["lat"];
		$lng = $_POST["lng"];
		$description = $_POST["description"];
	  	$description = (preg_replace("/[<>]/", "", $description));
    	$description = str_replace("'", "", $description);

		require 'findlocation.php';

		$insertLocation = "INSERT INTO locations (lat, lng, name, description, city, country)
		VALUES ('$lat', '$lng', '$location', '$description', '$city', '$country')";

		if (!mysqli_query($link,$insertLocation)) {
		  die('Error: ' . mysqli_error($link));
		}

	}
	// If an old location is submitted
	elseif (isset($_POST["addoldlocation"])) {
		$location = $_POST["addoldlocation"];	
	}
	// If post is a reply
	elseif (isset($_POST["reply"])) {
		$location = "";
	}
	// If no location is submitted
	else {
		$lat = $_POST["lat"];
		$lng = $_POST["lng"];
		require 'findlocation.php';
		$location = $city.", ".$country;
	}

	$toTxt = str_replace("'", "´", $newPost);
  	$toTxt = (preg_replace("/[^a-zA-Z0-9åäöÅÄÖ -:;,=´()@#]/", "", $toTxt));
	$toTxt = preg_replace_callback('/(http[s]?:(\S+))/',
		function($bitlyUrl) {
		$login = "maddeeasyrider";
		$appkey = "R_a835527cec7344eea3ca1de2dd2879cc";
        static $id = 0;
        $id++;
        return '<a href="'.get_bitly_short_url($bitlyUrl[1],$login,$appkey).'" class="aTag" target="_blank">'.get_bitly_short_url($bitlyUrl[1],$login,$appkey).'</a>';
    }, $toTxt);

	// Add post
	if (strlen($toTxt) < 141) {
		$day = date("Y-m-d H:i:s");
		$postId = MYUSER.$day;
		$postId = (preg_replace("/[ :-]/", "", $postId));


		
		if (isset($_POST["reply"])){
			// If post is a reply to other posts
			print "YEP!";
			$reply = $_POST["reply"];
			$sql="INSERT INTO replies (user, time, content, id, replies)
			VALUES ('".MYUSER."', '$day', '$toTxt', '$postId', '$reply')";
		} else{
			$sql="INSERT INTO posts (user, time, content, location, id)
			VALUES ('".MYUSER."', '$day', '$toTxt', '$location', '$postId')";
		}

		if (!mysqli_query($link,$sql)) {
		  die('Error: ' . mysqli_error($link));
		}

		$newPost = "";
		$location = "";
	}
	elseif (strlen($toTxt) > 140) {
		print "<div class='messages'>Please, stay within the character limit...</div>";
	}

	// Upload file if a file is submitted
	if (isset($_FILES['upload'])) {
		if ($_FILES['upload']['error']  ==  0) {
		  $tmp = $_FILES['upload']['tmp_name'];
		  $name = $_FILES['upload']['name'];
		  $size = $_FILES['upload']['size'];
		  $rest = substr($name, -4);
		  
		  if ($size > 2097152) {
		    printf("Filen $name är  %d  bytes för stor!", 2097152 - $size);
		  }
		  else {
		    move_uploaded_file($tmp,  "img/uploads/" . $postId . $rest);
		  }
		}
	}

	mysqli_close($link);
}

function newPost($newPost,$location, $postId){
	?>

    <div class="newpost">
	    <form enctype="multipart/form-data" action="<?php echo PAGENAME ?>?<?php print CURRENTGET ?>=<?php print CURRENT ?>" method="post">
			<input type="text" class="form-control" name="addnewlocation" id="addnewlocation" placeholder="Location" value="<?php print $location ?>" onkeyup="findSuggestions(this.value, 'locations')" autocomplete="off">
			<span id="locationsresult"></span>
			<textarea id="newpost" name="newpost" class="form-control" placeholder="What's on your mind? Don't forget to add a #tag and maybe a @user as well!"><?php print $newPost ?></textarea>
			<div id="newpostchars"></div>
			<input type="file" name="upload" />
			<input type="text" name="lat" id="lat" placeholder="GPS" class="gps form-control">
			<input type="text" name="lng" id="lng" placeholder="GPS" class="gps form-control">
	      	<button type="submit" class="btn btn-primary" name="submit" value="">Post</button>
	      	<div class="clear"></div>
		</form>
	</div>
    <script>
	    $(document).ready(function(){
	      $('textarea#newpost').keyup(function () {
	        var max = 140;
	        var len = $(this).val().length;
	        if (len > max) {
	          $('#newpostchars').text('! Oops, to many characters!');
	        } else {
	          var char = max - len;
	          $('#newpostchars').text(char + ' characters left');
	        }
	      });
	    });
    </script>
<?php
}

function replyPost($newPost,$location, $postId){
	?>

    <div class="newpost replytopost">
	    <form enctype="multipart/form-data" action="<?php echo PAGENAME ?>?<?php print CURRENTGET ?>=<?php print CURRENT ?>" method="post">
			<textarea id="newpost1" name="newpost" class="form-control" placeholder="Write a reply!"><?php print $newPost ?></textarea>
			<div id="newpostchars1"></div>
	      	<button type="submit" class="btn btn-primary" name="reply" value="<?php print $postId ?>">Post</button>
	      	<div class="clear"></div>
		</form>
	</div>
    <script>
	    $(document).ready(function(){
	      $('textarea#newpost1').keyup(function () {
	        var max = 140;
	        var len = $(this).val().length;
	        if (len > max) {
	          $('#newpostchars1').text('! Oops, to many characters!');
	        } else {
	          var char = max - len;
	          $('#newpostchars1').text(char + ' characters left');
	        }
	      });
	    });
    </script>
<?php
}