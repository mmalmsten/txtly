<?php

session_start();
session_destroy();

session_start();

$_SESSION['messages'] = array(
    array('status' => 'green', 'text' => 'You have signed out!'),
);

header('Location: form.php');
