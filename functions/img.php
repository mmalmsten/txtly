<?php

// Run function depending on if user has the right to change profile picture or not.
function img($user, $myUser, $directory) {
  if ($user !== $myUser){
    showImg($directory, $user);
  }
  else {
    uploadImg($directory, $user);
  }
}

// Upload profile picture
function uploadImg($directory, $user) {
  $pageName = basename($_SERVER['PHP_SELF']);

  if (isset($_FILES['upload']) && $_GET['upload'] == $directory) {
    if ($_FILES['upload']['error']  ==  0) {
      $tmp = $_FILES['upload']['tmp_name'];
      $name = $_FILES['upload']['name'];
      $size = $_FILES['upload']['size'];
      $rest = substr($name, -4);
      
      if ($size > 2097152) {
        printf("<div class='messages'>The chosen image is  %d  bytes too big!</div>", $size - 1048576);
      }
      else {
        move_uploaded_file($tmp,  "img/" . $directory . "/" . $user . $rest);
      }
    }
    else {
      print "<div class='messages'>Oops! It seems like something went wrong</div>";
    }
  }
  ?>
  <div class="<?php print $directory ?>img" style="background-image: url(img/<?php print $directory ?>/<?php print $user ?>.jpg);">
    <div class="hoverprofileimg show<?php print $directory ?>">Upload new image</div>
    <?php if ($directory == "header"): ?>
      <h1><?php print $user ?></h1>      
    <?php endif ?>
  </div>
  <div class="uploadprofileimg upload<?php print $directory ?>">
    <form enctype="multipart/form-data" action="<?php print $pageName ?>?name=<?php print CURRENT ?>&upload=<?php print $directory ?>" method="POST">
      Upload an image (maximum 1Mb):
      <input type="file" name="upload" />
      <input type="submit" class="btn btn-primary" value="Upload"  />
    </form>
  </div>
<?php
}

// Show profile picture 
function showImg($directory, $user) {
  $pageName = basename($_SERVER['PHP_SELF']);
  if ($pageName !== "profile.php") {
    $user = "txtly";
  }
  ?>
  <div class="<?php print $directory ?>img" style="background-image: url(img/<?php print $directory ?>/<?php print $user ?>.jpg);">
    <?php if ($directory == "header"): ?>
      <h1><?php print $user ?></h1>      
    <?php endif ?>
  </div>
<?php
}
?>