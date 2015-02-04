<?php
$newPost = "";
$location = "";
$lat = "";
$lng = "";
$description = "";
$postId = "";

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
	// If no location is submitted
	else {
		$lat = $_POST["lat"];
		$lng = $_POST["lng"];
		require 'findlocation.php';
		$location = $city.", ".$country;
	}

	if ($newPost !== "") {
		// Add post
	  	$toTxt = (preg_replace("/[<>]/", "", $newPost));
		$toTxt = preg_replace_callback('/(http[s]?:(\S+))/',
			function($bitlyUrl) {
			$login = "maddeeasyrider";
			$appkey = "R_a835527cec7344eea3ca1de2dd2879cc";
	        static $id = 0;
	        $id++;
	        return '<a href="'.get_bitly_short_url($bitlyUrl[1],$login,$appkey).'" class="aTag">'.get_bitly_short_url($bitlyUrl[1],$login,$appkey).'</a>';
	    }, $toTxt);

		$day = date("Y-m-d H:i:s");
		$postId = MYUSER.$day;
		$postId = (preg_replace("/[ :-]/", "", $postId));

		$sql="INSERT INTO posts (user, time, content, location, id)
		VALUES ('".MYUSER."', '$day', '$toTxt', '$location', '$postId')";

		if (!mysqli_query($link,$sql)) {
		  die('Error: ' . mysqli_error($link));
		}

		$newPost = "";
		$location = "";
	}

	// Upload file if a file is submitted
	if (isset($_FILES['upload'])) {
		if ($_FILES['upload']['error']  ==  0) {
		  $tmp = $_FILES['upload']['tmp_name'];
		  $name = $_FILES['upload']['name'];
		  $size = $_FILES['upload']['size'];
		  $rest = substr($name, -4);
		  
		  if ($size > 1048576) {
		    printf("Filen $name är  %d  bytes för stor!", 1048576 - $size);
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
	    <form enctype="multipart/form-data" action="<?php echo PAGENAME ?>" method="post">
			<input type="text" class="form-control" name="addnewlocation" id="addnewlocation" placeholder="Location" value="<?php print $location ?>" onkeyup="findSuggestions(this.value, 'locations')" autocomplete="off">
			<span id="locationsresult"></span>
			<textarea name="newpost" class="form-control" placeholder="What's on your mind? Don't forget to add a #tag and maybe a @user as well!"><?php print $newPost ?></textarea>
			<input type="file" name="upload" />
			<input type="text" name="lat" id="lat" placeholder="GPS" class="gps form-control">
			<input type="text" name="lng" id="lng" placeholder="GPS" class="gps form-control">
	      	<button class="btn btn-primary" name="submit" value="">Post</button>
	      	<div class="clear"></div>
		</form>
	</div>

<?php
}