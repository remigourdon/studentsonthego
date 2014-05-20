<?php

/**
 * This page allows an administrator to modify a city in the database.
 */

include_once("inc/HTMLTemplate.php");
include_once("inc/connstring.php");
include_once("inc/conversions.php");

// Tables
$tableCities = "cities";

// Open content
$content = <<<END
<div class="container">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Edit a city</h3>
            </div>
            <div class="panel-body">
END;

// An id is specified in GET
if(isset($_GET['id']) && $_GET['id'] != "") {

    $id = $_GET['id'];

    // There is data in POST, the form has been filled in
    if(!empty($_POST)) {

        // Get the data from POST
        $_name  = isset($_POST['_name']) ? $_POST['_name'] : ""; // Previous name
        $name   = isset($_POST['name']) ? $_POST['name'] : "";
        $lati   = isset($_POST['lati']) ? $_POST['lati'] : "";
        $long   = isset($_POST['long']) ? $_POST['long'] : "";

        // If some mandatory fields aren't filled in
        if($name == "" || $lati == "" || $long == "") {

            // Give information
            $content .= "<p>Please complete all the mandatory fields.</p>";
            $content .= "<p>" . $feedback . "</p><hr>";
            $content .= getForm($id, $name, $lati, $long);

        // If everything is fine, we can proceed to the query
        } else {

            // Prevent SQL injections and encode UTF-8 characters
            $name       = utf8_encode($mysqli->real_escape_string($name));

            // Prepare coordinates in wkt
            $wkt = "POINT({$lati} {$long})";

            $query = <<<END
            --
            -- Updates city in the database
            --
            UPDATE {$tableCities}
            SET name = '{$name}', coordinates = PointFromText('{$wkt}')
            WHERE ID = {$id};
END;

            // Performs query
            $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
            if($mysqli->affected_rows >= 1) {

                // Query was successful

                // Redirect the user
                header("Location: add-country.php");
                exit();

            // There was a problem during the update
            } else {

                // Provide new form and feedback
                $content .= "<p>Please try again.</p><hr>";
                $content .= getForm($id, $name, $lati, $long);

            }

        }

    // The user hasn't filled in the form yet
    } else {

        // We will query the database to get the current values
        $query = <<<END
        --
        -- Gets the current values in the database for the city
        --
        SELECT name, AsText(coordinates)
        FROM {$tableCities}
        WHERE id = {$id};
END;

        // Performs query
        $res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

        // The id is not found in the database
        if($res->num_rows < 1) {

            // Redirect the user
            header("Location: add-country.php");
            exit();

        // The city has been found
        } else {

            $city    = $res->fetch_object();
            $name    = utf8_decode($city->name);

            // Decode wkt
            $array  = get_object_vars($city);
            $coord  = point_to_coord($array["AsText(coordinates)"]);

            $content .= "<p>Fill all the mandatory fields.</p><hr>";
            $content .= getForm($id, $name, $coord[0], $coord[1]);

        }

        $res->close();

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
    <div class="col-md-3"></div>
</div>
END;

// Display
echo $header;
echo $content;
echo footer();


function getForm($id = "", $name = "", $lati = "", $long = "") {

    $name = htmlspecialchars($name);

    $html = <<<END
    <form role="form" action="mod-city.php?id={$id}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name (*):</label>
            <input type="text" class="form-control" name="name" id="name" value="{$name}">
            <input type="text" class="form-control" name="_name" id="_name" value="{$name}" style="display:none">
        </div>
        <div class="form-group col-md-6">
            <label for="lati">Latitude (*):</label>
            <input type="number" class="form-control" name="lati" id="lati" step="0.0001" min="-90" max="+90" value="{$lati}">
        </div>
        <div class="form-group col-md-6">
            <label for="long">Longitude (*):</label>
            <input type="number" class="form-control" name="long" id="long" step="0.0001" min="-180" max="+180" value="{$long}">
        </div>
        <button type="submit" class="btn btn-default">Insert</button>
    </form>
END;

    return $html;

}

?>