<?php

/**
 * Provides interface for editing and adding new countries to the database.
 */

$tableCountry = "country";
$content = "";

// Initialize fields variables
$countryName        = "";
$countryPopulation  = "";



// There is data in POST, the form has been filled in
if(!empty($_POST)) {

    $query = "";
    $countryName        = isset($_POST['countryName']) ? $_POST['countryName'] : "";
    $countryPopulation  = isset($_POST['countryPopulation']) ? $_POST['countryPopulation'] : "";

    // Check if the fields are filled in
    if(!file_exists($_FILES['countryGeometry']['tmp_name'])
        || !file_exists($_FILES['countryFlag']['tmp_name'])
        || $countryName == ""
        || $countryPopulation == "") {

        $content .= "<p>Please fill out all the fields.</p>";
        $content .= getForm($countryName, $countryPopulation);

    } else {

        if($_FILES['countryGeometry']['error'] > 0 || $_FILES["countryGeometry"]["type"] != "application/json") {

            $content .= "<p>Please try again and verify that your file is JSON.</p>";
            $content .= getForm($countryName, $countryPopulation);

        } else if($_FILES['countryFlag']['error'] > 0 || $_FILES["countryFlag"]["type"] != "image/png") {

            $content .= "<p>Please try again and verify that your file is PNG.</p>";
            $content .= getForm($countryName, $countryPopulation);

        } else {

            include_once("inc/conversions.php");
            include_once("inc/connstring.php");

            // Avoid SQL injections and encode UTF-8 characters
            $countryName = utf8_encode($mysqli->real_escape_string($countryName));

            // Get WKT string
            $json = file_get_contents($_FILES['countryGeometry']['tmp_name']);
            $wkt = json_to_wkt($json); // Now we have WKT format

            // Save the flag
            $flagName = $countryName . ".png";
            move_uploaded_file($_FILES["countryFlag"]["tmp_name"], "content/flags/" . $flagName);

            if(isset($_GET['cid'])) {

                $cid = $_GET['cid'];
                $query = <<<END
                --
                -- Updates the country in database
                --
                UPDATE {$tableCountry}
                SET countryName = '{$countryName}', countryPopulation = '{$countryPopulation}', countryFlag = '{$flagName}', countryGeometry = GeomFromText('{$wkt}')
                WHERE countryId = {$cid};
END;

            } else {

                $query = <<<END
                --
                -- Inserts a new country into the database
                --
                INSERT INTO {$tableCountry}(countryName, countryPopulation, countryFlag, countryGeometry)
                VALUES('{$countryName}', '{$countryPopulation}', '{$flagName}',GeomFromText('{$wkt}'));
END;

            }

            // Performs query
            $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

            $mysqli->close();

            // Informs the user of the success and invite him to send new data
            $content .= "<p>$countryName has been successfully added to the database.</p>";
            $content .= getForm("", "");

        }

    }

} else if(isset($_GET['cid'])) {    // The country parameter is specified

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
        $content .= getForm($countryName, $countryPopulation);

    } else {

        // Fetch the data
        $row = $res->fetch_object();
        $countryName = $row->countryName;

        $content .= "<p>Edit the data for $countryName.</p>";
        $content .= getForm($countryName, $countryPopulation);

    }

    $res->close();
    $mysqli->close();

} else { // POST is empty, the user has not filled in the form yet

    $content .= "<p>Fill out the fields bellow to add a new country.</p>";
    $content .= getForm($countryName, $countryPopulation);

}

echo $content;

function getForm($countryName, $countryPopulation) {

    $countryName = htmlspecialchars($countryName);

    $html = <<<END
    <form action="edit-country.php" method="post" enctype="multipart/form-data">
        <label for="countryName">Name (*):</label>
        <input type="text" name="countryName" id="countryName" value="{$countryName}"><br>
        <label for="countryPopulation">Population (*):</label>
        <input type="number" name="countryPopulation" id="countryPopulation" value="{$countryPopulation}"><br>
        <label for="countryGeometry">Geometry (*):</label>
        <input type="file" name="countryGeometry" id="countryGeometry"><br>
        <label for="countryFlag">Flag (*):</label>
        <input type="file" name="countryFlag" id="countryFlag"><br>
        <input type="submit" name="submit" value="Submit">
    </form>
END;

    return $html;

}

?>