<?php

function uploadImg($user, $directory) {

  if (isset($_POST['addimg']) && $_POST['addimg'] == $directory && isset($_FILES['upload'])) {
    if ($_FILES['upload']['error']  ==  0) {

      $showDir = "img/".$directory;
      $dir = scandir("$showDir");
      foreach ($dir as $key => $img){
        if (strpos($img, $user) !== false) {
          unlink($showDir."/".$img);
        }
      }

      $tmp = $_FILES['upload']['tmp_name'];
      $name = $_FILES['upload']['name'];
      $size = $_FILES['upload']['size'];
      $rest = $_FILES['upload']['type'];
      $rest = str_replace("image/", ".", $rest);

      if ($size > 2097152) {
        printf("<div class='messages'>The chosen image is  %d  bytes too big!</div>", $size - 2097152);
      }
      else {
        move_uploaded_file($tmp,  "img/" . $directory . "/" . $user . $rest);
      }
    }
    else {
      print "<div class='messages'>Oops! It seems like something went wrong</div>";
    }
  } 
}

// Show profile / header picture 
function img($user, $directory) {

  if ($user == MYUSER){
    uploadImg($user, $directory);
  }

  $showDir = "img/".$directory;
  $dir = scandir("$showDir");
  foreach ($dir as $key => $img){
    if (strpos($img, $user) !== false) {
      $file = $showDir."/".$img;
    }
  }

?>
  <div class="<?php print $directory ?>" style="background-image: url(<?php print $file ?>);">
    <?php if ($directory == "headerimg"): ?>
      <h1 class="hidden-xs"><?php print $user ?></h1>      
    <?php endif ?>
    <?php if ($user == MYUSER): ?>
      <div class="hoverprofileimg show<?php print $directory ?>">Upload new image</div>
    </div>
    <div class="sidebar uploadprofileimg upload<?php print $directory ?>">
      <form enctype="multipart/form-data" action="<?php print PAGENAME ?>?name=<?php print CURRENT ?>" method="POST">
        Upload an image (maximum 2Mb):
        <input type="file" name="upload" />
        <button type="submit" class="btn btn-primary" name="addimg" value="<?php print $directory ?>">UPLOAD</button>
      </form>
    <?php endif ?>
  </div>

<?php 
}