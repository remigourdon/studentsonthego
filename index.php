<?php
include_once("inc/HTMLTemplate.php");

// Query database for countries informations
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

file_put_contents("./content/json/country_global.json", wkt_to_json($data));


// Welcome panel
$welcPanel=<<<END
<main class="_head" id="content" role="main">
    <p class="lead">You will soon study abroad and you want to know how much it will cost you ? <br>You're in the right place !<p>
    <p style="text-align:right;"><em>Students on the go</em>. <strong>By</strong> students <strong>for</strong> students.</p>
</main>
END;

// Map
$map= "<div id='map'></div>";

echo $header;
echo $welcPanel;
echo $map;
echo footer("global");
?>
