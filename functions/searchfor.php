<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = 'What are you doing!? Stop that.';
    header('Location: ../form.php');
    die;
}

// Shows suggestions when searching with search field
include 'link.php';
$link = mysqli_connect($tablehost, $tableuser, $tablepass, $tabletable);
mysqli_connect_errno();

$userResult = mysqli_query($link, "SELECT * FROM users ORDER BY user");
$tagResult = mysqli_query($link, "SELECT content FROM posts ORDER BY time DESC");
$locationResult = mysqli_query($link, "SELECT * FROM locations ORDER BY name DESC");

$searchObjects = Array();

while ($row = mysqli_fetch_array($userResult)) {
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

// Look up all suggestions from array if $searchFor is'nt ""
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