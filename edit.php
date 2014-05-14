<?php

$tableCountry = "country";

$content = "";

if(!empty($_POST)) {
    $query = "";
    $name   = isset($_POST['name']) ? $_POST['name'] : "";

    if(!file_exists($_FILES['map']['tmp_name']) || $name == "") {
        echo "Please fill out all the fields.";
    } else {
        if($_FILES['map']['error'] > 0 || $_FILES["map"]["type"] != "application/json") {
            echo "Please try again and verify that your file is JSON.";
        } else {
            include_once("inc/conversions.php");
            include_once("inc/connstring.php");

            $name = $mysqli->real_escape_string($name);

            $json = file_get_contents($_FILES['map']['tmp_name']);

            $wkt = json_to_wkt($json); // Now we have WKT format

            $query = <<<END
            --
            -- Insert a new country into the database
            --
            INSERT INTO {$tableCountry}(countryName, countryMap)
            VALUES('{$name}', MultiPolygonFromText('{$wkt}'));
END;
            // Performs query
            $res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
        }
    }
} else {
    $content .= <<<END
<form action="edit.php" method="post" enctype="multipart/form-data">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name">
    <label for="map">Map:</label>
    <input type="file" name="map" id="map">
    <input type="submit" name="submit" value="Submit">
</form>
END;
}

echo $content;

?>