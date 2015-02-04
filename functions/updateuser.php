<?php
  include 'link.php';
  $link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
  mysqli_connect_errno();

  $postResult = mysqli_query($link, "SELECT * FROM users WHERE user = '".CURRENT."'");

  if ($postResult) {
    while ($row = mysqli_fetch_assoc($postResult)) {
      $name = $row['name'];
      $email = $row['email'];
      $user = $row['user'];
      $description = $row['description'];
      $pwd = $row['pass'];
    }
    mysqli_free_result($postResult);
  }

  if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $user = CURRENT;
    $description = $_POST['description'];
    
    if ($_POST['pwd'] !== "") {
      $pwd = md5($_POST['pwd']);
      $pwd1 = md5($_POST['pwd1']);
    }

    if ($pwd === $pwd1 && filter_var($email, FILTER_VALIDATE_EMAIL)){
      $updateUser=" UPDATE users 
                    SET name='$name', email='$email', description='$description', pass='$pwd'
                    WHERE user='$user'";
      if (!mysqli_query($link,$updateUser)) {
        die('Error: ' . mysqli_error($link));
      }
    }
    elseif ($pwd !== $pwd1) {
      $alert = "<div class='messages'>Make sure the passwords are correct!</div>";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $alert = "<div class='messages'>The given e-mail address does not seem to be valid!</div>";
    }
    else {
      $alert = "<div class='messages'>Oops! It seems like there's something wrong...</div>";
    } 
  }
  if (CURRENT == MYUSER) {
    $disable = "";
  } else {
    $disable = " disabled ";
  }
?>
<form action="<?php echo PAGENAME ?>?<?php echo CURRENTGET ?>=<?php echo CURRENT ?>" method="post">
    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
      <input type="text" name="name" class="form-control" placeholder="Name" value="<?php print $name ?>" <?php print $disable ?>>
    </div>
    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
      <input type="text" name="email" class="form-control" placeholder="Email" value="<?php print $email ?>" <?php print $disable ?>>
    </div>
    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
      <input type="text" name="user" class="form-control" placeholder="Username" value="<?php print $user ?>" disabled >
    </div>
    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
      <textarea name="description" class="form-control" placeholder="Username" <?php print $disable ?>><?php print $description ?></textarea>
    </div>
    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
      <input type="password" name="pwd" class="form-control" placeholder="Password" value="" <?php print $disable ?>>
    </div>
    <div class="input-group">
      <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
      <input type="password" name="pwd1" class="form-control" placeholder="Password" value="" <?php print $disable ?>>
    </div>
    <button class="btn btn-primary">Submit</button>
    <div class="clear"></div>
    <p><?php if (isset($alert)) {print $alert;} ?></p>
</form>