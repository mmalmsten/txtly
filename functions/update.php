<?php

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

  
  $link = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
  mysqli_connect_errno();

// Update user
if (isset($_POST['updateuser'])) {
	$user = $_POST['updateuser'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$status = $_POST['status'];
	$pass = $_POST['pass'];
	$pass1 = $_POST['pass1'];
	$pwd = md5($pass);
	$active = $_POST['active'];

	if (strlen($pass) < 1 || $pass !== $pass1) {
		$userResult = mysqli_query($link, "SELECT * FROM users WHERE user='$user'");
		mysqli_connect_errno();

		if ($userResult) {
			while ($row = mysqli_fetch_assoc($userResult)) {
				$pwd = $row['pass'];
			}
		}
	  	$alert = "<div class='messages'>There was either no passwords submitted, or the submitted passwords doesn't seem to match...</div>";		
	}

	if (filter_var($email, FILTER_VALIDATE_EMAIL)){
	  $postResult=" UPDATE users 
	                SET name='$name', email='$email', status='$status', pass='$pwd', active='$active'
	                WHERE user='$user'";
	  if (!mysqli_query($link,$postResult)) {
	    die('Error: ' . mysqli_error($link));
	  }
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	  $alert = "<div class='messages'>The given e-mail address does not seem to be valid!</div>";
	}
	else {
	  $alert = "<div class='messages'>Oops! It seems like there's something wrong...</div>";
	} 
}

// Update place
if (isset($_POST['updateplace'])) {
	$name = $_POST['updateplace'];
	$description = $_POST['description'];
	$city = $_POST['city'];
	$country = $_POST['country'];

	$postResult=" UPDATE locations 
	              SET description='$description', city='$city', country='$country'
	              WHERE name='$name'";
	if (!mysqli_query($link,$postResult)) {
		$alert = "<div class='messages'>Oops! It seems like there's something wrong...</div>";
		die('Error: ' . mysqli_error($link));
	}
}

mysqli_close($link);

if (isset($alert)) {print $alert;}