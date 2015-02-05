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
    <script src="js/bootstrap.min.js"></script>
    <script src="js/currentlocation.js"></script>
    <script src="js/suggestlocations.js"></script>
    <script src="js/infinitescroll.js"></script>

   </head>
  <body>
<div id="loadScript"></div>
    <nav class="navbar navbar-default" role="navigation">
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
            <li><a href="profile.php?name=<?php print MYUSER ?>">Profile</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php">Sign out</a></li>
            <li class="logo">
              <svg version="1.1" id="Layer_3_xA0_Image_1_" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 x="0px" y="0px" viewBox="0 0 496 363" enable-background="new 0 0 496 363" xml:space="preserve">
                <image overflow="visible" width="496" height="363" id="Layer_3_xA0_Image" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAfAAAAFrCAYAAAAuDdtmAAAACXBIWXMAAAsSAAALEgHS3X78AAAA
                GXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAC8RJREFUeNrs3d+L7GUdwPFnpgNe
                HJAMQwSxH3glMyaUkbgbGHSk6MKECoroIgI36ET0R3QleBOjxx9EqSGSFFkGQjdnE1KycAfqwou8
                SKgIsgvpQtierztz1NPMnln3eb7f7+c7rxc8CqvnzNlnvrvv83xmdmaUujfJa5bXTgKAftvPay+v
                edd/kJFYA0C8qLcVcOEGQMyDBFy0ARDyQAEXbgCoHPKSARduAGgp5KUCLt4A0GLITxtw4QaADiL+
                PvEGgFbdmNcteb2Y1z/aDrh4A0CHEX8vARdvAOg44icNuHgDQA8ifpKAizcA9CTimwZcvAGgRxHf
                JODiDQA9PYkfF++LeR1aby0A6tKao+5OxFvAAQR8yyIu3gIOIOA9jfjomNP3geto4/0CoFzAeVvz
                squ7q/7DeM0vmNkzAOjczrpT+HjN6dszzgGgH2abBtzpGwB6fgofO30DQLxT+NjpGwDincLHTt8A
                EO8UPnb6BoB4p/B3/lyzn727Mj8HDlCXFh3v0s+FL4PkhVsEHEDAA7VoOUI3PgeAgCdKf+NxAgdw
                Ao9hmte8OYFP7AUAhDFbniibdzvx42NO4ABO4IF6NLJZAg4g4PF6NLYHABDOxAncCRzACTyefQEX
                cAABD8gIHQAEHAAQcABAwAFAwAEAAQcABBwABBwAEHAAQMABQMABAAEHAAQcABBwABBwAEDAAQAB
                BwABBwAEHAAQcAAQcABAwAEAAQcAAQcABBwAEHAAQMABQMABAAEHAAQcAAQcABBwAEDAAUDAAQAB
                BwAEHAAEHAAQcABAwAEAAQcAAQcABBwAEHAAEHAAQMABAAEHAAEHAAQcABBwABBwWwAAAg4ACDgA
                IOAAIOAAgIADAAIOAAIOAAg4ACDgACDgAICAAwACDgAIOAAIOAAg4ACAgAOAgPfPQV5fW6x9dysA
                2+BwAGvnss/p8Uq3A4Am9WWF/wR+sOYimAk4gIAPdXkMHAACGkLAX1/z8X+7ewEQcABAwAEAAQcA
                AQcABBwAEHAAQMABQMABAAEHAAQcAAQcAIaneUvqaV6jK6xp6vjtqwUcANF+O9q7ec03+DXzxf/b
                WcgFHIBtD/em0e5VyAUcgG2N994pwr0q5HttRlzAARDvgBEXcADEO2DEBRyAbVIz3q1GXMAB2KbT
                97yl26oecQEHYJtO322a17xNAQdgW8yHdJsCDsA22B/abY87+kSWr3jT+UvRAbAV9oZ2220H/PKn
                77f+g+8AbKX50G677YCvevp+1Qf5AWCI2g74vId/MwIAAQcAhhfwyQk/DgD0IOCzFbGeLD4OAGzo
                TMu3t5PXgW0HgFgncADowmRoty3gAGyD2dBuW8AB2AY7HZ3CJ4vbFnAACHQKr3abAg6AU3iw07eA
                A7CNp/A2Il79R6QFHIBtO4XXjvgy3js1PxEBB0DEg8VbwAEQ8YDxFnAAtj3izauDXjxlyCeL3+Og
                rXgLOAC8t5Avo33YdriXzrjfAOBdIQ/BCRwAAhJwABBwAEDAAQABBwABBwAEHAAQcAAQcABAwAEA
                AQcAAY9o3QvIT929AAzVKB29k0p0u3ntXxbvlyvtFwD1HNqC7Qp4m/sFgIB3zmPgACDgAICAAwAC
                DgACDgAIOAAg4AAg4ACAgAMAAg4AAg4ACDgAIOAAgIADgIADAAIOAAg4AAg4ACDgAICAA4CAAwAC
                DgAIOAAIOAAg4ACAgAMAAg4AAg4ACDgAIOAAIOAAgIADAAIOAAIOAAg4ACDgACDgAICAAwACDgAI
                OAAIOAAg4ACAgAOAgAMAAg4ACDgACDgAIOAAgIADgIDbAgAQcABAwAEAAQcAAQcABBwAEHAAEHAA
                QMABAAEHAAEHAAQcABBwAEDAAUDAAQABBwAEHAAEHAAQcADgRPZH+R+H9mFjI1sAUJUmbWYq4AIO
                IOABe2SEDgABNQHftw0AEMZb3W5GwpO8DuzHRozQAeoyQr+yaV7zZZAu5rVjTwQcQMBjtGgZJKdw
                AQcQ8EAtWj6JbW4/AKD3Lj1vbbzqgwBAL+2tCviefQGAXp++L03ML39M12MPx/MYOEBdOrTe9J0B
                H6+oOwDQ49P3qhOlZ6M7gQM4gff89L3qBD53CgeAfp++VwW8sSfiANCbeK98kvm6kbBR+mpG6AB1
                GaG/2zStea2Wde9GZpQOAN2fvte+0NpxbydqlA4A3cX7VK/P0ozSmzc6ObSMdQBaoDVH3Z2U2EwR
                t6pfZPTKd33NW9Zwvq+KuHXcxcYwnM3rx65py4oR79EJIz5L3jec011H9NN9i6/tT9oK6MTyMe+N
                3x30pN94RRwBH5YPLb6mP2crIE68G+MT3sg8eXY6DMVn83pavCFevE9zcnISxwk8tvvT0ZPVgIDx
                Pu03XhFHwOP5yOLr9i5bAXHj3Rif4saN0yGWz+f1M/GG+PEueXJyGncCp99+mNe3bQMMI96lv/GK
                uIDTPx9dfF2esxUwjHAvjQv+4Zo/1G46eucUY3Xo3t15/Vy8YXjxrn1yciJ3Aqc7F/L6lm2AYUW7
                7W+8Qi7gtOdMXo/m9XVbAcMMd1ffeMVcwKnn5rwezut2WwHDDffSuOVPcPk4efMN32PlUM69eb0s
                3tBqsKeLno0WbZvbFrrifdLjuSqvx1K9d0f6UTJhARBwimpG5s9XCvcbeX3TFgMIOGWdz+vNSvH+
                bV432WIAAaecNkbmAAg4BX0s1RuZ/ycZmQMIOMWdr3jqbkbmH7bFAAJOOUbmAAh4MLfm9UKlcL+e
                1zdsMYCAU5aROQACHsjZvB6vGO9HbTGAgAt4WbVH5t7gBEDABbyw71c8dT+XjMwBBFzAi2pG5k8k
                I3MABDyMT6R6I/N/JSNzAAEX8OJmqe7I/EZbDCDgAl6OkTkAAh7MbXn9oVK4/5nXV20xgIALeFlG
                5gAIeCBX5/XTivF+xBYDIOBlNSPzl1K9kflXbDEAAl5WzZH5b5KROQACXlQzMn8yGZkDIOBh3J7q
                jcz/nozMARDw4mqOzJ/N6wZbDICAl2NkDoCAB3NHXn+qFO7X8vqSSxIAAS/LyBwAAQ/kmlR3ZP6Q
                yxAAAS+rGZkfpHoj83tcggAIeFk1R+a/SkbmAAh4Uc3I/KlkZA6AgIfx6VRvZP63ZGQOgIAX90DF
                U/czeV3vcgNAwMsxMgdAwIO5M695pXC/mtfdLjEABLwsI3MABDyQa1PdkfkFlxUAAl5WMzL/c6o3
                Mv+CSwoAAS+r5sj8l8nIHAABL6oZmT+djMwBEPAwPpPqjcz/mozMARDw4h6seOr+RV7XuXwAEPBy
                jMwBEPBgAT+X118qhfuVZGQOgIAXZ2QOgIAHCvgHk5E5AAIeKuDNyPyVVG9kfs4lAoCAl2VkDoCA
                Bwr4dYvA1or3gy4LAAS8rLuSkTkAhAr4hYqn7uZJcNe6HAAQ8HKMzAEgWMCbF06pNTJvXiPdyBwA
                AS/MyBwAAgXcyBwAggW8GZm/muqNzO90lwMg4GUZmQNAoIBfn9czFeP9gLsZAAEvG3AjcwAIFvCH
                Kp66n8rrA+5eAAS8XMCNzAEgWMC/mOqNzA+SkTkAAl484LVH5te4OwEQ8HIBNzIHgGABvyev11K9
                kfmuuxAAAS8bcCNzAAgU8Bvy+nXFeM/cbQAIeNmA1x6Z3+EuA4CyAX+44qn7ybze7+4CgHIBb0bm
                zyYjcwAIE/Avp3oj8z8mI3MAKB7wR1LdkfnV7h4AKBtwI3MACBjwGuulvD7lLgGAOAE3MgeAYAE3
                MgeAQAFvRua3uQsAIE7An0hG5gAQKuBG5gAQKOAvJiNzAAgV8GZkftZ2A0CcgBuZA0CggL+Q18dt
                MQDECbiROQAEC/j3bCsAxAl4MzK/1ZYCQJyAP5aMzAEgVMDP20YAiBPw3ycjcwAIFfBmZH6V7QOA
                OAE3MgeAQAF/Pq9bbBkAxAm4kTkABAr4m3l9xzYBQJyANyPzm20RAPTPf9fE+yfJyBwAeuu59P8j
                83ttCwD02015/W4R7+bfRuYAEIhXVAPouf8JMAA+wpBaRvgcIQAAAABJRU5ErkJggg==">
                </image>
              </svg>
            </li>
          </ul>
          <form class="navbar-form navbar-right" action="search.php" method="get">
            <div class="form-group">
              <input type="search" name="search" class="form-control" placeholder="Search for user or tag" onkeyup="findSuggestions(this.value, 'searchfor', 'searchFor')" autocomplete="off">
              <div id="searchforresult"></div>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
        </div>
      </div>
    </nav>
 
    <div class="hidden-xs"><?php img(CURRENT, "header") ?></div>
    <div class="wrapper">
      <div class="col-sm-3">
        <?php require 'sidebar.php'; ?>
      </div>

