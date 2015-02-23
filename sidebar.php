<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

function followBtn() {
  include 'functions/link.php';
  $link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
  mysqli_connect_errno();

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

  $follow = "follow";

  $follows = mysqli_query($link, "SELECT * FROM follow WHERE follower='".MYUSER."' AND follows='".CURRENT."'");

  if ($follows) {
    while ($row = mysqli_fetch_assoc($follows)) {
        $follow = "unfollow";
    }
  }

  mysqli_close($link); ?>

  <input type='submit' class="btn btn-xs btn-primary btn-follow" id="followBtn" name="follow<?php print CURRENT ?>" value="<?php print $follow ?>">

<?php } ?>
<?php if (CURRENTGET == "location" && placeInfo(CURRENT, 'name') == CURRENT): ?>
  <div class="sidebar">
    <h4><a href="place.php?location=<?php print placeInfo(CURRENT, 'lat') ?>"><?php print placeInfo(CURRENT, 'name') ?></a></h4>
    <p><?php print placeInfo(CURRENT, 'lat') ?>, <?php print placeInfo(CURRENT, 'lng') ?>
    <p><?php print placeInfo(CURRENT, 'city') ?>
    <br><?php print placeInfo(CURRENT, 'country') ?>
    <p><?php print placeInfo(CURRENT, 'description') ?>

    <div class="postattachment" id="<?php print placeInfo(CURRENT, 'name') ?>map"></div>
    <script type="text/javascript">

      if ("<?php print placeInfo(CURRENT, 'lat') ?>") {

          var myCenter=new google.maps.LatLng(<?php print placeInfo(CURRENT, 'lat') ?>, <?php print placeInfo(CURRENT, 'lng') ?>);

          var mapProp = {
          center:myCenter,
          zoom:18,
          scrollwheel: false,
          navigationControl: false,
          mapTypeControl: false,
          scaleControl: false,
          draggable: false,
          mapTypeId:google.maps.MapTypeId.SATELLITE
          };

          var map=new google.maps.Map(document.getElementById("<?php print placeInfo(CURRENT, 'name') ?>map"),mapProp);
          map.setTilt(45);

          var marker=new google.maps.Marker({
            position:myCenter,
          });

          marker.setMap(map);
      }

    </script>
  </div>
<?php endif ?>

<div id="loading">
  <span class="glyphicon glyphicon-pushpin"></span> Finding your position...
</div>
<div id="doneloading">
  <div class="profile-column">
      <?php newPost($newPost, $location, $postId) ?>
      <div id="locationinfo"></div>
  </div>
</div>
<?php if (PAGENAME == "profile.php"): ?>
  <?php img(CURRENT, "profileimg") ?>
  <div class="sidebar">
    <h4><a href="profile.php?name=<?php print userInfo(CURRENT, 'user') ?>"><?php print userInfo(CURRENT, 'name') ?></a></h4>
    <div id="updateuser">
    </div>
    <?php if (CURRENT == MYUSER): ?>
      <button class="btn btn-primary" id="updateuserbtn">Save changes</button>      
    <?php endif ?>
  </div>
  <div class="sidebar">
    <?php if (CURRENT !== MYUSER) {
      followBtn();
    } ?>
    <div id="followerbox"></div>
  </div>
<?php endif ?>
<div class="sidebar hidden-xs">
	<h4>Most popular tags</h4>
	<?php require 'functions/tag.php'; ?>
</div>