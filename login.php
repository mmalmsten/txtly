<?php
session_start();

include 'functions/link.php';
$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
mysqli_connect_errno();

$postResult = mysqli_query($link, "SELECT * FROM users");

if ($postResult) {
  while ($row = mysqli_fetch_assoc($postResult)) {
	$user = $row['user'];
  $user = strtolower($user);
	$pwd = $row['pass'];
  $active = $row['active'];
  $theArray[$user] = $pwd;
  $activeUser[$user] = $active;
  }

  mysqli_free_result($postResult);
}
mysqli_close($link);

$user = $_POST['user'];
$pwd = $_POST['pwd'];

if ($theArray[$user] !== md5($pwd)) {
    // Skicka tillbaka till login-sidan
    $_SESSION['messages'] = array(
        array('text' => 'Ogiltigt login.'),
    );
    header('Location: form.php');
} elseif ($activeUser[$user] == 0) {
    // Skicka tillbaka till login-sidan
    $_SESSION['messages'] = array(
        array('text' => 'It seems like your profile has been deactivated.'),
    );
    header('Location: form.php');
} else {
    // Logga in
    $_SESSION['user'] = $_POST['user'];
    //header('Location: '.$_SERVER['HTTP_REFERER']);
    header('Location: index.php');
}
