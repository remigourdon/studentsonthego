<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Test</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div id="map"></div>

    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/d3.min.js"></script>
    <script src="js/global-map.js"></script>
</body>
</html>

<?php

include_once("inc/connstring.php");
include_once("inc/conversions.php");

$query = "SELECT ID, name, population, AsText(geometry) FROM countries";

$res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

$data = array();

while($row = $res->fetch_array()) {

    $properties = array(
        "ID" => $row['ID'],
        "name" => $row['name'],
        "population" => $row['population']);

    $country = array($row['AsText(geometry)'] => $properties);

    $data = array_merge($data, $country);

}

file_put_contents("./content/data.json", wkt_to_json($data));

?>