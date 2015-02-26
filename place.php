<?php

require 'functions/header.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

  require 'functions/link.php';
  $link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
  mysqli_connect_errno();

  $postResult = mysqli_query($link, "SELECT name FROM locations WHERE name = '".CURRENT."'");
  $row = mysqli_fetch_assoc($postResult);
  
  if (!$row) { 
  	$searchterm = strtok(CURRENT, " ");
  	$searchterm = str_replace(",", "", $searchterm);
  ?>
      
    <div class='feed col-md-6 col-sm-9'>
    	<div class="foundnothing">
	    	<h1>
			    How terrible! <br>
			    <small>It seems like there's no existing check-in location called <strong><?php print CURRENT ?></strong>.
			    The check-in location might have been removed, but maybe the search field can lead you in the right direction?</small>
		    </h1>
			<p><form class="form-inline" action="search.php" method="get">
				<button type="submit" name="search" class="btn btn-primary" value="<?php print $searchterm ?>">Sure, let's search</button>
			</form>
		</div>
	</div>

  <?php } else { ?>

  <div class="col-sm-9">
    <div id="loadnextpage"></div>
    <button class="btn btn-lg btn-primary" id="loadnow">Load more posts</button>
    <div id="loadposts">Loading posts...</div>
  </div>

<?php } require 'functions/footer.php'; ?>