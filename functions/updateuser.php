<?php
  session_start();

  if (!isset($_SESSION['user'])) {
      $_SESSION['error'] = 'What are you doing!? Stop that.';
      header('Location: ../form.php');
      die;
  }

  define("MYUSER", $_SESSION['user']);
  define("CURRENT", $_POST["user"]);

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
      $description = str_replace("'", "", $description);
      //$pwd = $row['pass'];
    }
    mysqli_free_result($postResult);
  }
  
  if ($_POST['action'] == "update") {
    if (/*$pwd === $pwd && */filter_var($email, FILTER_VALIDATE_EMAIL)){
      $updateUser=" UPDATE users 
                    SET name='$name', email='$email', description='$description'
                    WHERE user='$user'";
      if (!mysqli_query($link,$updateUser)) {
        die('Error: ' . mysqli_error($link));
      }
    }
    /*elseif ($pwd !== $pwd1) {
      $alert = "<div class='messages'>Make sure the passwords are correct!</div>";
    }*/
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $alert = "<div class='messages'>The given e-mail address does not seem to be valid!</div>";
    }
    else {
      $alert = "<div class='messages'>Oops! It seems like there's something wrong...</div>";
    } 
  }
  mysqli_close($link);
?>

    <?php if (CURRENT == MYUSER): ?>    
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="<?php print $name ?>">
      </div>
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
        <input type="text" id="email" name="email" class="form-control" placeholder="Email" value="<?php print $email ?>">
      </div>
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
        <input type="text" id="user" name="user" class="form-control" placeholder="Username" value="<?php print $user ?>" disabled >
      </div>
      <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <textarea id="description" name="description" class="form-control" placeholder="Describe yourself width 100 chars"><?php print $description ?></textarea>
        <div id="chars"></div>
      </div>
      <div class="clear"></div>
      <p><?php if (isset($alert)) {print $alert;} ?></p>
    <?php endif ?>

    <?php if (CURRENT !== MYUSER): ?>    
      <p><?php print $description ?></p>
      <div class="clear"></div>
    <?php endif ?>

    <script>
    $(document).ready(function(){
      $('textarea#description').keyup(function () {
        var max = 100;
        var len = $(this).val().length;
        if (len >= max) {
          $('#chars').text(' you have reached the limit');
        } else {
          var char = max - len;
          $('#chars').text(char + ' characters left');
        }
      });
    });
    </script>