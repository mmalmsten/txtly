<?php

session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

define("MYUSER", $_SESSION['user']);
define("PAGENAME", "profile.php");
define("CURRENT", "madde");
define("CURRENTGET", "name");

// Show image
function showImage($postId) {
	$showDir = "img/uploads";
	$dir = scandir("$showDir");
	foreach ($dir as $key => $file){
		if (strpos($file, $postId) !== false) {
			return "$showDir/$file";
		}
	}
}

// $pagenumber is used in infinite scroll
function feed($pagenumber){

	require 'functions/newpost.php';

	require 'functions/link.php';
	$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
	mysqli_connect_errno();

	// $postresult depends on current page
	if (CURRENTGET == "index") {
		//$postResult = mysqli_query($link, "SELECT * FROM posts ORDER BY time DESC LIMIT $pagenumber, 10");
		$postResult = mysqli_query($link, "SELECT * FROM posts LEFT JOIN follow ON user = follows WHERE follower='".MYUSER."' ORDER BY time DESC LIMIT $pagenumber, 10");
	}
	else if (CURRENTGET == "location") {
		$postResult = mysqli_query($link, "SELECT * FROM posts WHERE location LIKE '%".CURRENT."%' ORDER BY time DESC LIMIT $pagenumber, 10");
	}
	else if (CURRENTGET == "search") {
		$postResult = mysqli_query($link, "SELECT * FROM posts WHERE content LIKE '%#".CURRENT."%' ORDER BY time DESC LIMIT $pagenumber, 10");
	}
	else if (CURRENTGET == "name"){
		$postResult = mysqli_query($link, "SELECT * FROM posts WHERE content LIKE '%@".CURRENT."%' OR user = '".CURRENT."' ORDER BY time DESC LIMIT $pagenumber, 10");
	}

	// Show posts
	if ($postResult) {
	  while ($row = mysqli_fetch_assoc($postResult)) {
	    $user = $row['user'];
	    $time = $row['time'];
	    $content = $row['content'];
		require 'functions/usertoname.php';	    
		$content = preg_replace('/(@([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="profile.php?name=\2" class="aName">\2</a>', $content);
		$content = preg_replace('/(#([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="search.php?search=\2" class="aTag">\1</a>', $content);
	    $location = $row['location'];
		$postId = $row['id'];

		if (CURRENTGET == "name" && $user !== MYUSER) {
			$class = "imTagged";
		} else{
			$class = "";
		}
?>

<div class="post <?php print $class; ?>" id="post<?php print $postId ?>">
	<div class="user">
		<?php if ($user == MYUSER || userInfo(MYUSER, 'status') == "admin" ) { ?>
			<button type="submit" name="drop" id="drop" value="<?php print $postId ?>" class="btn-clean"><i class="glyphicon glyphicon-remove"></i></button>
		<?php } ?>	
		<?php img($user, "thumbnailimg") ?>
		<span class="name"><a href="profile.php?name=<?php print $user ?>"><?php print userInfo($user, 'name') ?></a></span> <span class="time"><?php print $time; ?> <a href="place.php?location=<?php print $location ?>"><?php print $location ?></a></span>
	</div>
	<p><?php print $content ?></p>
	<div class="clear"></div>
	<div class="row">
	<?php if (PAGENAME !== "place.php" && placeInfo($location, 'lat') && !showImage($postId)): ?>
		<div class="col-sm-12">
			<div class="postattachment" id="<?php print $postId ?>map"></div>
		</div>
	<?php endif ?>
	<?php if (showImage($postId)): ?>
		<div class="col-sm-6">
			<div class="postattachment" style="background-image: url(<?php print showImage($postId) ?>);"></div>
		</div>
	<?php endif ?>
	<?php if (PAGENAME !== "place.php" && placeInfo($location, 'lat') && showImage($postId)): ?>
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
		$replyToPost = mysqli_query($link, "SELECT * FROM replies WHERE id='$postId' ORDER BY time ASC");
		if ($replyToPost) {
		  while ($row = mysqli_fetch_assoc($replyToPost)) {
		    $user = $row['user'];
		    $time = $row['time'];
		    $content = $row['content'];
			require 'functions/usertoname.php';	    
			$content = preg_replace('/(@([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="profile.php?name=\2" class="aName">\2</a>', $content);
			$content = preg_replace('/(#([a-zA-Z0-9_åäöÅÄÖ]+))/', '<a href="search.php?search=\2" class="aTag">\1</a>', $content);
		    $location = $row['location'];
			$postId = $row['id']; ?>

<div class="replies" id="postreply<?php print $postId ?>">
	<div class="user">
		<?php if ($user == MYUSER || userInfo(MYUSER, 'status') == "admin" ) { ?>
			<button type="submit" name="drop" id="drop" value="<?php print $postId ?>" class="btn-clean"><i class="glyphicon glyphicon-remove"></i></button>
		<?php } ?>	
		<?php img($user, "thumbnailimg") ?>
		<span class="name"><a href="profile.php?name=<?php print $user ?>"><?php print userInfo($user, 'name') ?></a></span> <span class="time"><?php print $time; ?> <a href="place.php?location=<?php print $location ?>"><?php print $location ?></a></span>
	</div>
	<p><?php print $content ?></p>
	<div class="clear"></div>
	<div class="row">
	<?php if (PAGENAME !== "place.php" && placeInfo($location, 'lat') && !showImage($postId)): ?>
		<div class="col-sm-12">
			<div class="postattachment" id="<?php print $postId ?>map"></div>
		</div>
	<?php endif ?>
	<?php if (showImage($postId)): ?>
		<div class="col-sm-6">
			<div class="postattachment" style="background-image: url(<?php print showImage($postId) ?>);"></div>
		</div>
	<?php endif ?>
	<?php if (PAGENAME !== "place.php" && placeInfo($location, 'lat') && showImage($postId)): ?>
		<div class="col-sm-6">
			<div class="postattachment" id="<?php print $postId ?>map"></div>
		</div>
	<?php endif ?>
	</div>
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

		  }
		  mysqli_free_result($replyToPost);
		}
	replyPost($newPost, $location, $postId) ?>
</div>

	  <?php }
	  mysqli_free_result($postResult);
	}
	mysqli_close($link);
}

require 'functions/info.php';
require 'functions/img.php';

$getpage = 0;

feed($getpage);
?>

<script>
	$(document).ready(function(){
		$("button#drop").click(function() {
			buttonID = $(this).val();
			alert("GO! "+buttonID);
			$.ajaxSetup({
			    beforeSend: function() {
			    	//$('#loadposts').fadeIn(); 
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
			    	alert(response);
			    	console.log(response);
				}
			});
		});
	});
</script>