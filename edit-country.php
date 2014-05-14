<?php

/**
 * Provides interface for editing and adding new countries to the database.
 */

$tableCountry = "country";
$content = "";

// No country is selected, thus the user intends to add a new one
if(!isset($_GET['cid'])) {
    // There is data in POST, the form has been filled in
    if(!empty($_POST)) {
        $query = "";
        $name   = isset($_POST['name']) ? $_POST['name'] : "";

        if(!file_exists($_FILES['map']['tmp_name']) || $name == "") {
            $content .= "<p>Please fill out all the fields.</p>";
            $content .= getForm($name);
        } else {
            if($_FILES['map']['error'] > 0 || $_FILES["map"]["type"] != "application/json") {
                $content .= "<p>Please try again and verify that your file is JSON.</p>";
                $content .= getForm($name);
            } else {
                include_once("inc/conversions.php");
                include_once("inc/connstring.php");

                // Avoid SQL injections and encode UTF-8 characters
                $name = utf8_encode($mysqli->real_escape_string($name));

                $json = file_get_contents($_FILES['map']['tmp_name']);

                $wkt = json_to_wkt($json); // Now we have WKT format

                $query = <<<END
                --
                -- Inserts a new country into the database
                --
                INSERT INTO {$tableCountry}(countryName, countryMap)
                VALUES('{$name}', GeomFromText('{$wkt}'));
END;
                // Performs query
                $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

                $mysqli->close();

                // Informs the user of the success and invite him to send new data
                $content .= "<p>$name has been successfully added to the database.</p>";
                $content .= getForm("");
            }
        }
    // POST is empty, the user has not filled in the form yet
    } else {
        $content .= "<p>Fill out the fields bellow to add a new country.</p>";
        $content .= getForm($name);
    }
} else {    // The country parameter is specified
    include_once("inc/connstring.php");

    $cid = $_GET["cid"];

    $query = <<<END
    --
    -- Gets the country in the database
    --
    SELECT countryName
    FROM {$tableCountry}
    WHERE countryId = {$cid};
END;

    // Performs query
    $res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

    if($res->num_rows < 1) {
        $content .= "<p>The country you're looking for does not exist in database.</p>";
        $content .= getForm("");
    } else {
        // Fetch the data
        $row = $res->fetch_object();
        $name = $row->countryName;

        $content .= "<p>Edit the data for $name.</p>";
        $content .= getForm($name);
    }

    $res->close();
    $mysqli->close();
}

echo $content;

function getForm($name) {
    $name = htmlspecialchars($name);

    $html = <<<END
    <form action="edit.php" method="post" enctype="multipart/form-data">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="{$name}"><br>
        <label for="map">Map:</label>
        <input type="file" name="map" id="map"><br>
        <input type="submit" name="submit" value="Submit">
    </form>
END;

    return $html;
}

?>