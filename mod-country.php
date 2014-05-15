<?php

/**
 * This page allows an administrator to modify a country in the database.
 */

include_once("inc/HTMLTemplate.php");
include_once("inc/connstring.php");

// Tables
$tableCountries = "countries";

// Open content
$content = <<<END
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">Edit a country</div>
        <div class="panel-body">
END;

// An id is specified in GET
if(isset($_GET['id']) && $_GET['id'] != "") {

    $id = $_GET['id'];

    // There is data in POST, the form has been filled in
    if(!empty($_POST)) {

        // Get the data from POST
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $popu = isset($_POST['popu']) ? $_POST['popu'] : "";

        // Check for the files
        $geom = (!file_exists($_FILES['geom']['tmp_name'])
                    || $_FILES['geom']['error'] > 0
                    || $_FILES["geom"]["type"] != "application/json") ? false : true;
        $flag = (!file_exists($_FILES['flag']['tmp_name'])
                    || $_FILES['flag']['error'] > 0
                    || $_FILES["flag"]["type"] != "image/png") ? false : true;
        $feedback = "Please check the file(s): ";
        $feedback .= !$geom ? "`geometry` " : "";
        $feedback .= !$flag ? "`flag`" : "";

        // If some mandatory fields aren't filled in
        if($name == "") {

            // Give information
            $content .= "<p>Please complete all the mandatory fields.</p>";
            $content .= "<p>" . $feedback . "</p>";
            $content .= getForm($id, $name, $popu);

        // If everything is fine, we can proceed to the query
        } else {

            include_once("inc/conversions.php");

            // Prepare flag data
            $flagName    = $flag ? $name . ".png" : "";

            // Prevent SQL injections and encode UTF-8 characters
            $name       = utf8_encode($mysqli->real_escape_string($name));
            $flagName   = utf8_encode($mysqli->real_escape_string($flagName));

            // Prepare data and SQL for geometry
            $geomSQL = "geometry = geometry";
            if($geom) {
                $wkt        = json_to_wkt(file_get_contents($_FILES['geom']['tmp_name']));
                $geomSQL    = "geometry = GeomFromText('{$wkt}')";
            }

            // Prepare SQL for non mandatory fields
            //$cptlSQL = ($cptl != "") ? "capitalID = '{$cptl}'" : "capitalID = capitalID";
            $cptlSQL = "capitalID = capitalID";
            $popuSQL = ($popu != "") ? "population = '{$popu}'" : "population = population";
            $flagSQL = $flag ? "flag = '{$flagName}'" : "flag = flag";

            $query = <<<END
            --
            -- Updates country in the database
            --
            UPDATE {$tableCountries}
            SET name = '{$name}', {$popuSQL}, {$geomSQL}, {$flagSQL}, {$cptlSQL}
            WHERE ID = {$id};
END;

            // Performs query
            $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
            if($mysqli->affected_rows >= 1) {

                // Query was successful, we can save the files
                if($flag)
                    move_uploaded_file($_FILES['flag']['tmp_name'], "content/flags/" . $flagName);

                // Redirect the user
                header("Location: add-country.php");
                exit();

            // There was a problem during the update
            } else {

                // Provide new form and feedback
                $content .= "<p>Please try again.</p>";
                $content .= getForm($id, $name, $popu);

            }

        }

    // The user hasn't filled in the form yet
    } else {

        // We will query the database to get the current values
        $query = <<<END
        --
        -- Gets the current values in the database for the country
        --
        SELECT name, population
        FROM {$tableCountries}
        WHERE id = {$id};
END;

        // Performs query
        $res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

        // The id is not found in the database
        if($res->num_rows < 1) {

            // Redirect the user
            header("Location: add-country.php");
            exit();

        // The country has been found
        } else {

            $row    = $res->fetch_object();
            $name   = utf8_decode($row->name);
            $popu   = $row->population;

            $content .= "<p>Fill all the mandatory fields.</p>";
            $content .= getForm($id, $name, $popu);

        }

    }

$mysqli->close();

// id is missing
} else {

    // Redirect the user
    header("Location: add-country.php");
    exit();

}

// Close content
$content .= <<<END
        </div>
    </div>
</div>
END;

// Display
echo $header;
echo $content;
echo $footer;


function getForm($id = "", $name = "", $popu = "") {

    $name = htmlspecialchars($name);

    $html = <<<END
    <form role="form" action="mod-country.php?id={$id}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name (*):</label>
            <input type="text" class="form-control" name="name" id="name" value="{$name}"><br>
        </div>
        <div class="form-group">
            <label for="popu">Population:</label>
            <input type="number" class="form-control" name="popu" id="popu" value="{$popu}"><br>
        </div>
        <div class="form-group">
            <label for="geom">Geometry:</label>
            <input type="file" name="geom" id="geom"><br>
            <p class="help-block">GeoJSON with .json extension</p>
        </div>
        <div class="form-group">
            <label for="flag">Flag:</label>
            <input type="file" name="flag" id="flag"><br>
            <p class="help-block">PNG file</p>
        </div>
        <button type="submit" class="btn btn-default">Update</button>
    </form>
END;

    return $html;

}

?>