		<div class="clear"></div>
	</div>
</div>
    <script type="text/javascript">
    	// Show "upload form" when clicking on profile picture
  		$(document).ready(function(){
		  $(".showprofile").click(function(){
		    $(".uploadprofile").slideDown("slow");
		  });
		  var width = $('.profileimg').width();
		  $('.profileimg').css('height', width);
		});

		$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
		})


	  	$(document).ready(function(){
			var pageNumber = 0;

			//alert("GO!");
			$.ajaxSetup({
			    beforeSend: function() {
			    	$('#loadposts').fadeIn(); 
			    	$('#loadnow').fadeOut(); 
			 	},
			    complete: function() {
			    	$('#loadposts').fadeOut(); 
					$('#loadnow').fadeIn(); 
			    }
			});
			$.ajax({
			    url: 'feed.php',
			    type: 'post',
			    data: {
					"page": pageNumber,
					"pagename": "<?php print PAGENAME ?>",
					"current": "<?php print CURRENT ?>",
					"currentget": "<?php print CURRENTGET ?>",
			    },
			    success: function(response) { 
			    	//alert(response);
			    	//console.log(response);
					$("#loadnextpage").append(response);
				}
			});
			
			$("#loadnow").click(function() {
				pageNumber = pageNumber + 10;
				//alert("GO!");
				$.ajaxSetup({
				    beforeSend: function() {
				    	$('#loadposts').fadeIn(); 
				    	$('#loadnow').fadeOut(); 
				 	},
				    complete: function() {
				    	$('#loadposts').fadeOut();
						$('#loadnow').fadeIn(); 
				    }
				});
				$.ajax({
				    url: 'feed.php',
				    type: 'post',
				    data: {
						"page": pageNumber,
						"pagename": "<?php print PAGENAME ?>",
						"current": "<?php print CURRENT ?>",
						"currentget": "<?php print CURRENTGET ?>",
				    },
				    success: function(response) { 
				    	//alert(response);
				    	console.log(response);
						$("#loadnextpage").append(response);
					}
				});
			});
		});
	</script>
  </body>
</html>