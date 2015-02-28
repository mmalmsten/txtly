<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
} ?>

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

<?php if (PAGENAME == "index.php" || PAGENAME == "profile.php"): ?>
  <div id="loading">
    <span class="glyphicon glyphicon-pushpin"></span> Finding your position...
  </div>
  <div id="doneloading">
    <div class="profile-column">
        <?php newPost($newPost, $location, $postId) ?>
        <div id="locationinfo"></div>
    </div>
  </div>
<?php endif ?>
<?php if (PAGENAME == "profile.php"): ?>
  <?php img(CURRENT, "profileimg") ?>
  <div class="sidebar">
    <?php require 'functions/follow.php'; ?>
  </div>
<?php endif ?>
<div class="sidebar hidden-xs">
	<h4>Most popular tags</h4>
	<?php require 'functions/tag.php'; ?>
</div>