<?php

require 'functions/header.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

if (userInfo(MYUSER, 'status') !== "admin") { ?>
    <div class='feed col-md-6 col-sm-9'>
		<div class="foundnothing">
			<h1>
			  What are you doing here?! <br>
			  <small>Go to <a href="index.php">your feed</a>.</small>
			</h1>
		</div>
	</div>
<?php die; } ?>

<div class="col-sm-9">
	<table class="table post">
	<?php
		require 'functions/link.php';
		$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
		mysqli_connect_errno();
		$userResult = mysqli_query($link, "SELECT * FROM users ORDER BY user");

		mysqli_connect_errno();

		if ($userResult) {
			while ($row = mysqli_fetch_assoc($userResult)) { ?>
				<tr class="<?php print $row["user"] ?>row active<?php print $row["active"] ?>">
					<td>@<?php print $row["user"] ?></td>
					<td><?php print $row["name"] ?></td>
					<td><?php print $row["status"] ?></td>
					<td><button type="submit" name="activeValue" id="activeValue" value="<?php print $row["user"].$row["active"] ?>" class="btn-clean"><i class="glyphicon"></i></button></td>
					<td><button type="submit" name="drop" id="drop" value="<?php print $row["name"] ?>" class="btn-clean"><i class="glyphicon glyphicon-trash"></i></button></td>
				</tr>
			<?php }
			mysqli_free_result($userResult);
		} ?>
	</table>
	<table class="table post">
	<?php
		$userResult = mysqli_query($link, "SELECT * FROM locations ORDER BY name");

		mysqli_connect_errno();

		if ($userResult) {
			while ($row = mysqli_fetch_assoc($userResult)) {
				$removeThisId = strtolower($row["name"]);
				$removeThisId = (preg_replace("/[^a-z]/", "", $removeThisId));
			?>
				<tr class="<?php print $removeThisId ?>row">
					<td><?php print $row["name"] ?></td>
					<td><?php print $row["description"] ?></td>
					<td><?php print $row["city"] ?>
					<br><?php print $row["country"] ?></td>
					<td><button type="submit" name="drop" id="drop" value="<?php print $row["name"] ?>" class="btn-clean"><i class="glyphicon glyphicon-trash"></i></button></td>
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
			var button = this;

			$.ajaxSetup({
			    beforeSend: function() {

			 	},
			    complete: function() {
			    	$($(button).parent()).parent().fadeOut()
			    }
			});
			$.ajax({
			    url: 'functions/deleteadmin.php',
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
			var button = this;
			var parent = $($(button).parent()).parent();
			$.ajaxSetup({
			    beforeSend: function() {
		   			$("button[value='"+buttonID+"'] i").addClass("glyphicon-pencil");
		   			$("#activeValue").attr("disabled");
			 	},
			    complete: function() {
			    	if ($(parent).hasClass("warning")) {
			    		$(parent).removeClass("warning");
		   				$($(button).children()).removeClass("glyphicon-ok");
		   				$($(button).children()).addClass("glyphicon-remove");
			    	} else{
			    		$(parent).addClass("warning");
		   				$($(button).children()).removeClass("glyphicon-remove");
		   				$($(button).children()).addClass("glyphicon-ok");
			    	};
		   			$("#activeValue").removeAttr("disabled");
			    }
			});
			$.ajax({
			    url: 'functions/blockuser.php',
			    type: 'post',
			    data: {
					"activeValue": buttonID,
			    },
			    success: function(response) { 
				}
			});
		});

		function colorBlocked() {
		   $("tr.active1").removeClass("warning");
		   $("tr.active0").addClass("warning");
		   $("tr.active1 td button#activeValue i").addClass("glyphicon-remove");
		   $("tr.active0 td button#activeValue i").addClass("glyphicon-ok");
		}
		colorBlocked();
	});
</script>