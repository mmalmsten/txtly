<?php
require 'functions/header.php';
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}
?>

  <div class="col-sm-9">
    <div id="loadnextpage"></div>
    <button class="btn btn-xs btn-primary" id="loadnow">Load more posts</button>
    <div id="loadposts">Loading posts...</div>
  </div>


<?php require 'functions/footer.php'; ?>