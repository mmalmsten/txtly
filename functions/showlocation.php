<?php

?>

	<span id="locationsresult"></span>
<div class="form-inline">
	<input type="text" class="form-control" name="location" id="newpostlocation" placeholder="Location" value="<?php print $location ?>" onkeyup="findSuggestions(this.value, 'locations')">