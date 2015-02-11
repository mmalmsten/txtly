<?php

require 'functions/header.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

if ($_SESSION['user'] !== "madde") {
	print "What are you doing???";
    die;	
}
?>
<div class="col-sm-9">
	<table class="table post">
	<?php
		require 'functions/link.php';
		$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
		mysqli_connect_errno();
		$userResult = mysqli_query($link, "SELECT * FROM users");

		mysqli_connect_errno();

		if ($userResult) {
			while ($row = mysqli_fetch_assoc($userResult)) { ?>
				<tr class="<?php print $row["user"] ?>row active<?php print $row["active"] ?>">
					<td>@<?php print $row["user"] ?></td>
					<td><?php print $row["name"] ?></td>
					<td><button type="submit" name="activeValue" id="activeValue" value="<?php print $row["user"].$row["active"] ?>" class="btn-clean"><i class="glyphicon"></i></button></td>
					<td><button type="submit" name="drop" id="drop" value="<?php print $row["user"] ?>" class="btn-clean"><i class="glyphicon glyphicon-trash"></i></button></td>
				</tr>
			<?php }
			mysqli_free_result($userResult);
		}
		mysqli_close($link); ?>
	</table>
</div>

<script>
	$(document).ready(function(){
		$("button#drop").click(function() {
			var buttonID = $(this).val();

			$.ajaxSetup({
			    beforeSend: function() {

			 	},
			    complete: function() {
			    	$('.'+buttonID+'row').fadeOut();
			    }
			});
			$.ajax({
			    url: 'functions/deleteuser.php',
			    type: 'post',
			    data: {
					"drop": buttonID
			    },
			    success: function(response) { 

				}
			});
		});
	});
	$(document).ready(function(){
		$("button#activeValue").click(function() {
			var buttonID = $(this).val();
			$.ajaxSetup({
			    beforeSend: function() {

			 	},
			    complete: function() {
					colorBlocked(buttonID);
			    }
			});
			$.ajax({
			    url: 'functions/blockuser.php',
			    type: 'post',
			    data: {
					"activeValue": buttonID,
			    },
			    success: function(response) { 
			    	alert(response);
				}
			});
		});

		function colorBlocked(id) {
		   $("tr.active1").removeClass("warning");
		   $("tr.active0").addClass("warning");
		   $("tr.active1 td button#activeValue i").addClass("glyphicon-remove");
		   $("tr.active0 td button#activeValue i").addClass("glyphicon-ok");
		}
		colorBlocked();
	});
</script>