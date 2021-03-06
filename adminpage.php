<?php

require 'functions/header.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: form.php');
    die;
}

require 'functions/delete.php';
require 'functions/update.php';

?>


<div class="col-sm-12">
	<table class="table post">
		<tr>
			<th>User</th>
			<th class="hidden-xs">Name</th>
			<th class="hidden-xs">Email</th>
			<th class="hidden-xs">New password</th>
			<th class="hidden-xs">Repeat new password</th>
			<?php if (userInfo(MYUSER, 'status') == "admin"): ?>
				<th class="hidden-xs">Status</th>
				<th>Block/Unblock user</th>
				<th class="hidden-xs">Delete user</th>
			<?php endif; ?>
			<th>Update</th>
		</tr>
	<?php
		$link = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
		mysqli_connect_errno();
		if (userInfo(MYUSER, 'status') == "admin"){
			$userResult = mysqli_query($link, "SELECT * FROM users ORDER BY name");
		} else{
			$userResult = mysqli_query($link, "SELECT * FROM users WHERE user='".MYUSER."'");
		}

		mysqli_connect_errno();

		if ($userResult) {
			while ($row = mysqli_fetch_assoc($userResult)) { ?>
				<form enctype="multipart/form-data" action="<?php echo PAGENAME ?>" method="post">
					<tr class="<?php print $row["user"] ?>row">
						<td><input type="text" class="form-control" name="user" value="<?php print $row["user"] ?>" disabled></td>
						<td class="hidden-xs"><input type="text" class="form-control" name="name" value="<?php print $row["name"] ?>"></td>
						<td class="hidden-xs"><input type="text" class="form-control" name="email" value="<?php print $row["email"] ?>"></td>
						<td class="hidden-xs"><input type="password" class="form-control" placeholder="password" name="pass" value=""></td>
						<td class="hidden-xs"><input type="password" class="form-control" placeholder="repeat password" name="pass1" value=""></td>
						<?php if (userInfo(MYUSER, 'status') == "admin"): ?>
							<td class="hidden-xs">
								<select class="form-control" name="status">
								  <option value="<?php print $row["status"] ?>">-- Choose --</option>
								  <option value="">user</option>
								  <option value="admin">admin</option>
								</select>
							</td>
							<td>
								<select class="form-control" name="active">
								  <option value="<?php print $row["active"] ?>">-- Choose --</option>
								  <option value="1">Active</option>
								  <option value="0">Inactive</option>
								</select>
							</td>
							<td class="hidden-xs"><button type="submit" name="drop" id="drop" value="<?php print $row["user"] ?>" class="btn-clean"><i class="glyphicon glyphicon-trash"></i></button></td>
						<?php endif; ?>
						<td><button type="submit" name="updateuser" id="update" value="<?php print $row["user"] ?>" class="btn-clean"><i class="glyphicon glyphicon-ok"></i></button></td>
					</tr>
				</form>
			<?php }
			mysqli_free_result($userResult);
		} ?>
	</table>
	<?php if (userInfo(MYUSER, 'status') == "admin"): ?>
		<table class="table post">
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th class="hidden-xs">City</th>
				<th class="hidden-xs">Country</th>
				<th></th>
				<th></th>
			</tr>
		<?php
			$locationResult = mysqli_query($link, "SELECT * FROM locations ORDER BY name");

			mysqli_connect_errno();

			if ($locationResult) {
				while ($row = mysqli_fetch_assoc($locationResult)) {
					$removeThisId = strtolower($row["name"]);
					$removeThisId = (preg_replace("/[^a-z]/", "", $removeThisId));
				?>
					<form enctype="multipart/form-data" action="<?php echo PAGENAME ?>" method="post">
						<tr class="<?php print $removeThisId ?>row">
							<td><input type="text" class="form-control" name="name" value="<?php print $row["name"] ?>" disabled></td>
							<td><input type="text" class="form-control" name="description" value="<?php print $row["description"] ?>"></td>
							<td><input type="text" class="form-control hidden-xs" name="city" value="<?php print $row["city"] ?>"></td>
							<td><input type="text" class="form-control hidden-xs" name="country" value="<?php print $row["country"] ?>"></td>
							<td><button type="submit" name="updateplace" id="update" value="<?php print $row["name"] ?>" class="btn-clean"><i class="glyphicon glyphicon-ok"></i></button></td>
							<td><button type="submit" name="drop" value="<?php print $row["name"] ?>" class="btn-clean drop"><i class="glyphicon glyphicon-trash"></i></button></td>
						</tr>
					</form>
				<?php }
				mysqli_free_result($locationResult);
			}
			mysqli_close($link); ?>
		</table>
	<?php endif ?>
	<div class="clear"></div>
</div>
<?php require 'functions/footer.php'; ?>