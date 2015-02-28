<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

	$link = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
	mysqli_connect_errno();

	$postResult = mysqli_query($link, "SELECT * FROM posts ORDER BY time");

	$allContents = "";

	if ($postResult) {
		while ($row = mysqli_fetch_assoc($postResult)) {
			$user = $row['user'];
			$time = $row['time'];
			$content = $row['content'];
			$allContents .= $content . " ";
		}

   		$tags = array();
   		preg_match_all('/(?<!\w)#\w+/',$allContents,$matches);
		foreach($matches as $value) {
		    $tags += $value;
		}

		$acv=array_count_values($tags);
		arsort($acv);
		$uniqueTags=array_keys($acv);
		$tagNumber = array_count_values( $tags );

		$tmp = 1;
		foreach ($uniqueTags as $tag) {
		$showTag = str_replace("#", "", $tag);
?>
			<a href="search.php?search=<?php print $showTag ?>"><button class="btn btn-tags btn-xs" type="button">
				<?php print $showTag ?> <span class="badge"><?php print $tagNumber["$tag"] ?></span>
			</button></a><br>

<?php
			if ($tmp++ >= 5) {
				break;
			}
		}

	  	mysqli_free_result($postResult);
	}
	mysqli_close($link);