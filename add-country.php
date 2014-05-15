<?php

/**
 * This page allows an administrator to add a new country to the database.
 */

include_once("inc/HTMLTemplate.php");

// Tables
$tableCountries = "countries";

// Open content
$content = <<<END
<div class="container">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add a new country</h3>
            </div>
            <div class="panel-body">
END;

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
                || ($_FILES['flag']["type"] != "image/png"
                    && $_FILES['flag']["type"] != "image/jpg"
                    && $_FILES['flag']["type"] != "image/jpeg")) ? false : true;
    $feedback = "Please check the file(s): ";
    $feedback .= !$geom ? "`geometry` " : "";
    $feedback .= !$flag ? "`flag`" : "";

    // If some mandatory fields aren't filled in
    if($name == "" || !$geom) {

        // Give information
        $content .= "<p>Please complete all the mandatory fields.</p>";
        $content .= "<p>" . $feedback . "</p><hr>";
        $content .= getForm($name, $popu);

    // If everything is fine, we can proceed to the query
    } else {

        include_once("inc/conversions.php");
        include_once("inc/connstring.php");


        // Prepare flag data
        $ext = end((explode(".", $_FILES['flag']['name'])));
        $flagName    = $flag ? $name . "." . $ext : "";

        // Prevent SQL injections and encore UTF-8 characters
        $name       = utf8_encode($mysqli->real_escape_string($name));
        $flagname   = utf8_encode($mysqli->real_escape_string($flagName));

        // Prepare data and SQL for geometry
        $wkt        = json_to_wkt(file_get_contents($_FILES['geom']['tmp_name']));
        $geomSQL    = "GeomFromText('{$wkt}')";

        $query = <<<END
        --
        -- Inserts a new country in the database
        --
        INSERT INTO {$tableCountries}(name, population, geometry, flag)
        VALUES('{$name}', '{$popu}', {$geomSQL}, '{$flagName}');
END;

        // Performs query
        $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
        $mysqli->close();

        // Query was successful, we can save the files
        if($flag)
            move_uploaded_file($_FILES['flag']['tmp_name'], "content/flags/" . $flagName);

        // Redirect the user
        header("Location: add-country.php");
        exit();

    }

// The user hasn't filled in the form yet
} else {

    $content .= "<p>Fill all the mandatory fields.</p><hr>";
    $content .= getForm();

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
echo $footer;


function getForm($name = "", $popu = "") {

    $name = htmlspecialchars($name);

    $html = <<<END
    <form role="form" action="add-country.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Name (*):</label>
            <input type="text" class="form-control" name="name" id="name" value="{$name}">
        </div>
        <div class="form-group">
            <label for="popu">Population:</label>
            <input type="number" class="form-control" name="popu" id="popu" value="{$popu}">
        </div>
        <div class="form-group col-md-6">
            <label for="geom">Geometry (*):</label>
            <input type="file" name="geom" id="geom">
        </div>
        <div class="form-group col-md-6">
            <label for="flag">Flag:</label>
            <input type="file" name="flag" id="flag">
        </div>
        <button type="submit" class="btn btn-default">Insert</button>
    </form>
END;

    return $html;

}

?>