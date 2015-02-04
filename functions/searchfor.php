<?php

include 'link.php';
$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
mysqli_connect_errno();

$postResult = mysqli_query($link, "SELECT * FROM users ORDER BY user");
$tagResult = mysqli_query($link, "SELECT content FROM posts ORDER BY time DESC");
$locationResult = mysqli_query($link, "SELECT * FROM locations ORDER BY name DESC");

$searchObjects = Array();

while ($row = mysqli_fetch_array($postResult)) {
    $searchObjects[] = $row['user'];  
}

if ($tagResult) {
    while ($row = mysqli_fetch_assoc($tagResult)) {
        $content = $row['content'];
        $content = explode(" ",$content);

        foreach ($content as $key => $value) {
            if (0 === strpos($value, '#')) {
                $value = preg_replace('/(#)/', '', $value);
                $value = strtolower($value);
                $searchObjects[] = $value;
            }
        }
    }
}

if ($locationResult) {
    while ($row = mysqli_fetch_array($locationResult)) {
        $searchObjects[] = $row['name'];  
    }
}

$searchObjects = array_unique($searchObjects);

mysqli_close($link);

// get the parameter from URL
$searchFor = $_REQUEST["searchFor"];

$suggestion = "";

// lookup all suggestions from array if $searchFor is different from "" 
if ($searchFor !== "") {
    $searchFor = strtolower($searchFor);
    $len=strlen($searchFor);
    foreach($searchObjects as $nameOrTag) {
        if (stristr($searchFor, substr($nameOrTag, 0, $len))) {
            if ($suggestion === "") {
                $suggestion = '<div class="col-xs-12 col-sm-3" id="searchresults"><a href="search.php?search=' . $nameOrTag . '">' . $nameOrTag . '</a>';
            } else {
                $suggestion .= ', <a href="search.php?search=' . $nameOrTag . '">' . $nameOrTag . '</a>';
            }
        }
    }
}

$suggestion .= '</div>';

// Output "no suggestion" if no suggestion was found or output correct values 
echo $suggestion === "" ? "" : $suggestion;