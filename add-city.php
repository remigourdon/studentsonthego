<?php

/**
 * This page allows an administrator to add a new city to the specified country.
 */

include_once("inc/HTMLTemplate.php");
include_once("inc/connstring.php");

// if user isn't admin
if( ! isset($_SESSION["username"])){
    // redirect to home page
    header("Location: index.php");
}

// Tables
$tableCountries = "countries";
$tableCities    = "cities";

// Open content
$content = <<<END
<div class="container">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add a new city</h3>
            </div>
            <div class="panel-body">
END;

// There is no id specified in the post
if(!isset($_GET['id']) || $_GET['id'] == "") {

    // Redirect the user
    header("Location: index.php");
    exit();

}



// We now verify if the id matches a country in the database
$id = $_GET['id'];

$query = <<<END
--
-- Check if the country exists in database
--
SELECT ID
FROM {$tableCountries}
WHERE ID = {$id};
END;

// Performs query
$res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

// The country does not exist
if($res->num_rows < 1) {

    // Redirect the user
    header("Location: index.php");
    exit();

}

$res->close();



// There is data in POST, the form has been filled in
if(!empty($_POST)) {

    // Get the data from POST
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $popu = isset($_POST['popu']) ? $_POST['popu'] : "";
    $lati = isset($_POST['lati']) ? $_POST['lati'] : "";
    $long = isset($_POST['long']) ? $_POST['long'] : "";

    // If some mandatory fields aren't filled in
    if($name == "" || $popu == "" || $lati == "" || $long == "") {

        // Give information
        $content .= "<p>Please complete all the mandatory fields.</p>";
        $content .= getForm($id, $name, $popu, $lati, $long);

    // If everything is fine, we can proceed to the query
    } else {

        include_once("inc/conversions.php");

        // Prevent SQL injections and encode UTF-8 characters
        $name       = utf8_encode($mysqli->real_escape_string($name));

        // Prepare coordinates in wkt
        $wkt = "POINT({$lati} {$long})";

        $query = <<<END
        --
        -- Inserts a new city in the database
        --
        INSERT INTO {$tableCities}(name, population, coordinates, countryID)
        VALUES('{$name}', '{$popu}', PointFromText('{$wkt}'), '{$id}');
END;

        // Performs query
        $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

        // Query was successful

        // Redirect the user
        header("Location: country.php?id={$id}");
        exit();

    }


// The user hasn't filled in the form yet
} else {

    $content .= "<p>Fill all the mandatory fields.</p><hr>";
    $content .= getForm($id);

}

// Close content
$content .= <<<END
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>
END;

$mysqli->close();

// Display
echo $header;
echo $content;
echo footer();


function getForm($id = "", $name = "", $popu = "", $lati = "", $long = "") {

    $name = htmlspecialchars($name);

    $html = <<<END
    <form role="form" action="add-city.php?id={$id}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name (*):</label>
            <input type="text" class="form-control" name="name" id="name" value="{$name}">
        </div>
        <div class="form-group">
            <label for="popu">Population:</label>
            <input type="number" min="0" class="form-control" name="popu" id="popu" value="{$popu}">
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