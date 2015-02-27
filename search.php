<?php

require 'functions/header.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

if ($_SERVER["REQUEST_METHOD"] == "GET"){
  $search = @$_GET["search"];
  $searchresult = (preg_replace("/[^a-zA-Z0-9åäöÅÄÖ]/", "", $search));
}

// Show images in feed
function showImage($postId, $dir) {
  $showDir = "img/".$dir;
  $dir = scandir("$showDir");
  foreach ($dir as $key => $file){
    if (strpos($file, $postId) !== false) {
      return "$showDir/$file";
    }
  }
}

function showSearch($searchFor){
  include 'functions/link.php';
  $link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
  mysqli_connect_errno();

  if ($searchFor == "users") {
    $postResult = mysqli_query($link, "SELECT * FROM users WHERE user LIKE '".CURRENT."%' OR name LIKE '".CURRENT."%' ORDER BY name");
  }
  else if ($searchFor == "locations") {
    $postResult = mysqli_query($link, "SELECT * FROM locations WHERE name LIKE '".CURRENT."%' ORDER BY name");
  }

  if ($postResult) {
    if ($postResult) {
      print '<table class="table post">';
      while ($row = mysqli_fetch_assoc($postResult)) {
        if ($searchFor == "users") {
          $name = $row['user'];
          $info = $row['name'];
        ?>
          <tr>
            <td><div class="thumbnailimg" style="background-image: url(<?php print showImage($name, "profileimg") ?>);"></div></td>
            <td><a href="profile.php?name=<?php print $name ?>"><?php print $info ?></a></td>
            <td><?php print $name ?></td>
          </tr>
        <?php
        }
        else if ($searchFor == "locations") {
          $name = $row['name'];
          $info = $row['city'].", ".$row['country']." | ".$row['description'];
         ?>
          <tr>
            <td></td>
            <td><a href="place.php?location=<?php print $name ?>"><?php print $name ?></a></td>
            <td><?php print $info ?></td>
          </tr>
        <?php 
        }
      }
      print '</table>';
    }
    mysqli_free_result($postResult);
  }
  mysqli_close($link);
}

?>

  <div class="col-sm-9">
    <?php
        print '<h1>Showing all results for "' . $searchresult . '"</h1>';
        showSearch("users");
        showSearch("locations");
    ?>
    <div id="loadnextpage"></div>
    <button class="btn btn-lg btn-primary" id="loadnow">Load more posts</button>
    <div id="loadposts">Loading posts...</div>
  </div>

<?php require 'functions/footer.php'; ?>