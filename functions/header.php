<?php
session_start();

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}


define("MYUSER", $_SESSION['user']);

if (isset($_GET["name"])) {
  define("CURRENT", $_GET["name"]);
  define("CURRENTGET", "name");
}
else if (isset($_GET["search"])) {
  define("CURRENT", $_GET["search"]);
  define("CURRENTGET", "search");
}
else if (isset($_GET["location"])) {
  define("CURRENT", $_GET["location"]);
  define("CURRENTGET", "location");
}
else{
  define("CURRENT", "index");
  define("CURRENTGET", "index");
}

define("PAGENAME", basename($_SERVER['PHP_SELF']));

require 'functions/info.php';
require 'functions/newpost.php';
require 'functions/img.php';

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Txtly</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="http://maps.googleapis.com/maps/api/js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

   </head>
  <body>
<div id="loadScript"></div>
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a href="profile.php?name=<?php print MYUSER ?>">My profile</a></li>

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <?php if (MYUSER == "madde"): ?>
              <li><a href="adminuser.php">Admin page</a></li>
            <?php endif ?>
            <li><a href="logout.php">Sign out</a></li>
          </ul>
          <form class="navbar-form navbar-right" action="search.php" method="get">
            <div class="form-group">
              <input type="search" name="search" class="form-control" placeholder="Search for user or tag" onkeyup="findSuggestions(this.value, 'searchfor')" autocomplete="off">
              <div id="searchforresult"></div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
        </div>
      </div>
    </nav>
 
    <?php if (PAGENAME == "profile.php"): ?>
      <?php img(CURRENT, "headerimg") ?>
    <?php endif ?>
    <?php if (CURRENTGET == "location"): ?>
      <div class="headerimg" id="<?php print placeInfo(CURRENT, 'name') ?>header"></div>
    <?php endif ?>

    <?php if (CURRENTGET == "location"): ?>
      <script type="text/javascript">

        if ("<?php print placeInfo(CURRENT, 'lat') ?>" !== "") {
          var myCenter=new google.maps.LatLng(<?php print placeInfo(CURRENT, 'lat') ?>, <?php print placeInfo(CURRENT, 'lng') ?>);

          var mapProp = {
          center:myCenter,
          zoom:16,
          scrollwheel: false,
          navigationControl: false,
          mapTypeControl: false,
          scaleControl: false,
          draggable: false,
          mapTypeId:google.maps.MapTypeId.SATELLITE
          };

          var map=new google.maps.Map(document.getElementById("<?php print placeInfo(CURRENT, 'name') ?>header"),mapProp);
          map.setTilt(45);

          var marker=new google.maps.Marker({
            position:myCenter,
          });

          marker.setMap(map);
        }
      </script>
    <?php endif ?>

    <div class="wrapper">
      <div class="col-sm-3">
        <?php require 'sidebar.php'; ?>
      </div>


