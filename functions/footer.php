		<div class="clear"></div>
	</div>
</div>
    <script src="http://maps.googleapis.com/maps/api/js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/location.js"></script>
    <script src="js/posts.js"></script>
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

	$(function () {
	  $('[data-toggle="tooltip"]').tooltip()
	})
	</script>
  </body>
</html>