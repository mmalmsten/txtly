<?php

session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

require 'functions/link.php';
define("MYUSER", $_SESSION['user']);
define("PAGENAME", $_POST['pagename']);
define("CURRENT", $_POST['current']);
define("CURRENTGET", $_POST['currentget']);

// Show images in feed
function showImage($postId, $dir) {
	$showDir = "img/".$dir;
	if ($dir == "profileimg") {
  		$dirAndFile = $showDir."/sample.jpg";
	}
	$dir = scandir("$showDir");
	foreach ($dir as $key => $file){
		$file1 = strstr($file, '.', true);
		if ($file1 == $postId) {
			$dirAndFile = "$showDir/$file";
		}
	}
	if (isset($dirAndFile)) {
		return $dirAndFile;
	}
}

// $pagenumber is used in infinite scroll
function feed($pagenumber){
	require 'functions/newpost.php';
	$link = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
	mysqli_connect_errno();

	$query = "SELECT * FROM posts ";

	// Parts of $query depends on current page
	if (PAGENAME == "index.php") {
		$query .= "LEFT JOIN follow ON user = follows WHERE follower='".MYUSER."'";
	}
	else if (PAGENAME == "place.php") {
		$query .= "WHERE location LIKE '%".CURRENT."%'";
	}
	else if (PAGENAME == "search.php") {
		$query .= "WHERE content LIKE '%#".CURRENT."%'";
	}
	else if (PAGENAME == "profile.php"){
		$query .= "WHERE content LIKE '%@".CURRENT."%' OR user = '".CURRENT."'";
	}

	$query .= " ORDER BY time DESC LIMIT $pagenumber, 10";
	$postResult = mysqli_query($link, $query);

	// Show posts
	if ($postResult) {
	  while ($row = mysqli_fetch_assoc($postResult)) {
	    $user = $row['user'];
	    $time = $row['time'];
	    $content = $row['content'];
		//require 'functions/usertoname.php';	    
		$content = preg_replace('/(@([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="profile.php?name=\2" class="aName">\2</a>', $content);
		$content = preg_replace('/(#([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="search.php?search=\2" class="aTag">\1</a>', $content);
	    $location = $row['location'];
		$postId = $row['id']; ?>
		
		<div class="post" id="post<?php print $postId ?>">
			<div class="user">
				<?php if ($user == MYUSER || userInfo(MYUSER, 'status') == "admin" ) { ?>
					<button type="submit" name="drop" id="drop" value="<?php print $postId ?>" class="btn-clean"><i class="glyphicon glyphicon-remove"></i></button>
				<?php } ?>	
				<div class="thumbnailimg" style="background-image: url(<?php print showImage($user, "profileimg") ?>);"></div>
				<span class="name"><a href="profile.php?name=<?php print $user ?>"><?php print userInfo($user, 'name') ?></a></span> <span class="time"><?php print $time; ?> <a href="place.php?location=<?php print $location ?>"><?php print $location ?></a></span>
			</div>
			<p><?php print $content ?></p>
			<div class="clear"></div>
			<div class="row">
				<?php if (showImage($postId, "uploads")): ?>
					<div class="col-sm-6">
						<div class="postattachment" style="background-image: url(<?php print showImage($postId, "uploads") ?>);"></div>
					</div>
				<?php endif ?>
				<?php if (PAGENAME !== "place.php" && placeInfo($location, 'lat')): ?>
					<div class="col-sm-6">
						<div class="postattachment" id="<?php print $postId ?>map"></div>
					</div>
				<?php endif ?>
			</div>
		<?php if (placeInfo($location, 'lat')): ?>
			<script type="text/javascript">
				var str = "<?php print placeInfo($location, 'lat'); ?>";
				if (str !== "") {
					var myCenter=new google.maps.LatLng(<?php print placeInfo($location, 'lat'); ?>, <?php print placeInfo($location, 'lng'); ?>);
				    var mapProp = {
					    center:myCenter,
					    zoom:12,
					    scrollwheel: false,
					    navigationControl: false,
					    mapTypeControl: false,
					    scaleControl: false,
					    draggable: false,
					    mapTypeId:google.maps.MapTypeId.ROADMAP
				    };
				    var map=new google.maps.Map(document.getElementById("<?php print $postId ?>map"),mapProp);
				    var marker=new google.maps.Marker({
				    	position:myCenter,
				    });
				    marker.setMap(map);
			    };
			</script>
		<?php endif;

		//Show replies
		$replyToPost = mysqli_query($link, "SELECT * FROM replies WHERE replies='$postId' ORDER BY time ASC");
		if ($replyToPost) {
		  while ($row = mysqli_fetch_assoc($replyToPost)) {
		    $user = $row['user'];
		    $time = $row['time'];
		    $content = $row['content'];
			require 'functions/usertoname.php';	    
			$content = preg_replace('/(@([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="profile.php?name=\2" class="aName">\2</a>', $content);
			$content = preg_replace('/(#([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="search.php?search=\2" class="aTag">\1</a>', $content);
			$postId = $row['replies'];
			$thisId = $row['id']; ?>

			<div class="replies" id="post<?php print $thisId ?>">
				<div class="user">
					<?php if ($user == MYUSER || userInfo(MYUSER, 'status') == "admin" ) { ?>
						<button type="submit" name="drop" id="drop" value="<?php print $thisId ?>" class="btn-clean"><i class="glyphicon glyphicon-remove"></i></button>
					<?php } ?>	
					<div class="thumbnailimg" style="background-image: url(<?php print showImage($user, "profileimg") ?>);"></div>
					<span class="name"><a href="profile.php?name=<?php print $user ?>"><?php print userInfo($user, 'name') ?></a></span> <span class="time"><?php print $time; ?> <a href="place.php?location=<?php print $location ?>"><?php print $location ?></a></span>
				</div>
				<p><?php print $content ?></p>
			</div>

		  <?php }
		  mysqli_free_result($replyToPost);
		}
		replyPost($newPost, $location, $postId);
	//End post
	print "</div>";
	}
	mysqli_free_result($postResult);
	}
	mysqli_close($link);
}

require 'functions/info.php';

$getpage = $_POST['pagenumber'];

feed($getpage); ?>

<script>
	$(document).ready(function(){
	    $("button#drop").click(function() {
	        buttonID = $(this).val();
	        $.ajaxSetup({
	            beforeSend: function() {
	            },
	            complete: function() {
	                $('#post'+buttonID).fadeOut();
	            }
	        });
	        $.ajax({
	            url: 'functions/deletepost.php',
	            type: 'post',
	            data: {
	                "drop": buttonID
	            },
	            success: function(response) { 
	                console.log(response);
	            }
	        });
	    });
	});
</script>