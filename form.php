<?php
session_start();

if (isset($_SESSION['messages'])) {
    foreach ($_SESSION['messages'] as $msg) {
        echo '<div class="messages">'.$msg['text'].'</div>';
    }
    unset($_SESSION['messages']);
}

if (isset($_SESSION['user'])) {
    header('Location: index.php');
    die;
}

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
  </head>
  <body>

    <div class="bgimg"></div>

    <div class="row around">
    <div class="col-sm-6">
      <form action="login.php" method="post">
        <div class="loginform">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" name="user" class="form-control" placeholder="Username">
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
            <input type="password" name="pwd" class="form-control" placeholder="Password">
          </div>
          <button class="btn btn-primary">Sign in</button>
          <div class="clear"></div>
        </div>
      </form>
    </div>
    <div class="col-sm-6">
      <?php require 'signup.php'; ?>
      <form action="form.php" method="post">
        <div class="loginform signup">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" name="name" class="form-control" placeholder="Name" value="<?php print $name ?>">
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input type="text" name="email" class="form-control" placeholder="Email" value="<?php print $email ?>">
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input type="text" name="user" class="form-control" placeholder="Username" value="<?php print $user ?>">
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
            <input type="password" name="pwd" class="form-control" placeholder="Password" value="<?php print $showpwd ?>">
          </div>
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
            <input type="password" name="pwd1" class="form-control" placeholder="Password" value="<?php print $showpwd1 ?>">
          </div>
          <button class="btn btn-primary">Sign up</button>
          <div class="clear"></div>
          <p><?php print $alert ?></p>
        </div>
      </form>
    </div>
    </div>
  </body>
</html>