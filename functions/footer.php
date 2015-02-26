<?php if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
} ?>
		<div class="clear"></div>
	</div>
</div>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/posts.js"></script>
    <script src="js/location.js"></script>
    <script src="js/img.js"></script>

    <script type="text/javascript">

   	$(document).ready(function(){
   		var pageNumber = 0;
   		var pagename = "<?php print PAGENAME ?>";
  		var current = "<?php print CURRENT ?>";
  		var currentget = "<?php print CURRENTGET ?>";
  		loadPosts(pageNumber, pagename, current, currentget);

  		$("#loadnow").click(function(){
  			pageNumber += 10;
  			loadPosts(pageNumber, pagename, current, currentget);
  		});
  	});
	</script>
  </body>
</html>