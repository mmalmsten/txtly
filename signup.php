<?php

$name = "";
$email = "";
$user = "";
$showpwd = "";
$showpwd1 = "";
$alert = "";
$active = 1;

if (isset($_POST['user'])) {
  include 'functions/link.php';
  $link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
  mysqli_connect_errno();

  $name = (preg_replace("/[^a-zA-ZåäöÅÄÖ -]/", "", $_POST['name']));
  $email = $_POST['email'];
  $user = (preg_replace("/[^a-zA-Z0-9]/", "", $_POST['user']));
  $user = strtolower($user);
  $pwd = md5($_POST['pwd']);
  $pwd1 = md5($_POST['pwd1']);
  $showpwd = $_POST['pwd'];
  $showpwd1 = $_POST['pwd1'];


  $postResult = mysqli_query($link, "SELECT * FROM users WHERE user = '$user'");

  if (!mysqli_fetch_assoc($postResult) && $pwd === $pwd1 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $insertLocation="INSERT INTO users (name, email, user, pass, active)
    VALUES ('$name', '$email', '$user', '$pwd', '$active')";
    if (!mysqli_query($link,$insertLocation)) {
      die('Error: ' . mysqli_error($link));
    }
    $alert = "<div class='messages'>$name, your user '$user' was sucessfully created.</div>";
    $name = "";
    $email = "";
    $user = "";
    $showpwd = "";
    $showpwd1 = "";
  }
  elseif ($pwd !== $pwd1) {
    $alert = "<div class='messages'>Make sure the passwords are correct!</div>";
  }
 elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $alert = "<div class='messages'>The given e-mail address does not seem to be valid!</div>";
  }
  else {
    $alert = "<div class='messages'>Oops! It seems like the user already exists</div>";
  }

  mysqli_close($link);
}

?>
