<?php

// Show only locations close to current location
$latmin = ($_REQUEST["lat"] - (($_REQUEST["lat"]) / 1000));
$latmax = ($_REQUEST["lat"] + (($_REQUEST["lat"]) / 1000));
$lngmin = ($_REQUEST["lng"] - (($_REQUEST["lat"]) / 1000));
$lngmax = ($_REQUEST["lng"] + (($_REQUEST["lat"]) / 1000));

include 'link.php';
$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
mysqli_connect_errno();

$postResult = mysqli_query($link, "SELECT * FROM locations 
    WHERE lat > $latmin AND lat < $latmax AND lng > $lngmin AND lng < $lngmax
    ORDER BY name");

$locations = Array();
while ($row = mysqli_fetch_array($postResult)) {
    $locations[] =  $row['name'];  
}

mysqli_close($link);

$searchFor = $_REQUEST["searchFor"];
$suggestion = "";

if ($searchFor !== "") {
    $searchFor = strtolower($searchFor);
    $len=strlen($searchFor);
    foreach($locations as $name) {
        if (stristr($searchFor, substr($name, 0, $len))) {
                $suggestion .= "<option value='".$name."'>$name</option>";
        }
    }
}

if ($suggestion !== "") {
	$suggestion = "<select name='addoldlocation' class='form-control' multiple>" . $suggestion . "</select>";
	print $suggestion;
}
else{
	print '<textarea class="form-control" name="description" id="description" placeholder="Description"></textarea>';
}