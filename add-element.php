<?php

/**
 * This page allows an administrator to add a new elements to the specified
 * country.
 */

include_once("inc/HTMLTemplate.php");
include_once("inc/connstring.php");

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
                <h3 class="panel-title">Add a new element</h3>
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
    $type = isset($_POST['type']) ? $_POST['type'] : "";
    $name = isset($_POST['name']) ? $_POST['name'] : "";
    $desc = isset($_POST['desc']) ? $_POST['desc'] : "";

    // Check for the files
    $pict = (!file_exists($_FILES['pict']['tmp_name'])
                || $_FILES['pict']['error'] > 0
                || $_FILES['pict']["type"] != "image/png") ? false : true;
    $feedback = "Please check the file(s): ";
    $feedback .= !$pict ? "`picture` " : "";

    // If some mandatory fields aren't filled in
    if($name == "" || $desc == "" || !$pict) {

        // Give information
        $content .= "<p>Please complete all the mandatory fields.</p>";
        $content .= "<p>" . $feedback . "</p>";
        $content .= getForm($id, $name, $desc);

    // If everything is fine, we can proceed to the query
    } else {

        include_once("inc/conversions.php");

        // Prepare flag data
        $pictName    = $pict ? $name . ".png" : "";

        // Prevent SQL injections and encode UTF-8 characters
        $name       = utf8_encode($mysqli->real_escape_string($name));
        $desc       = utf8_encode($mysqli->real_escape_string($desc));
        $pictName   = utf8_encode($mysqli->real_escape_string($pictName));

        //

        if($type == "city") {

            $query = <<<END
            --
            -- Inserts a new city in the database
            --
            INSERT INTO {$tableCities}(name, description, picture, countryID)
            VALUES('{$name}', '{$desc}', '{$pictName}', '{$id}');
END;

        } else {

            // Unvalid type
            // Redirect the user
            header("Location: index.php");
            exit();

        }

        // Performs query
        $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

        // Query was successful, we can save the files
        if($pict)
            move_uploaded_file($_FILES['pict']['tmp_name'], "content/picts/" . $pictName);

        // Redirect the user
        header("Location: add-country.php?id={$id}");
        exit();

    }


// The user hasn't filled in the form yet
} else {

    $content .= "<p>Fill all the mandatory fields.</p>";
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
echo $footer;


function getForm($id = "", $name = "", $desc = "") {

    $name = htmlspecialchars($name);

    $html = <<<END
    <form role="form" action="add-element.php?id={$id}" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="type">Type (*):</label>
            <select class="form-control" name="type" id="type">
                <option value="city" selected>City</option>
            </select>
        </div>
        <div class="form-group">
            <label for="name">Name (*):</label>
            <input type="text" class="form-control" name="name" id="name" value="{$name}">
        </div>
        <div class="form-group">
            <label for="desc">Description (*):</label>
            <textarea class="form-control" name="desc" id="desc">{$desc}</textarea>
        </div>
        <div class="form-group col-md-6">
            <label for="lati">Latitude (*):</label>
            <input type="number" class="form-control" name="lati" id="lati" step="0.0001" min="-90" max="+90" value="{$lati}">
        </div>
        <div class="form-group col-md-6">
            <label for="long">Longitude (*):</label>
            <input type="number" class="form-control" name="long" id="long" step="0.0001" min="-180" max="+180" value="{$long}">
        </div>
        <div class="form-group">
            <label for="pict">Picture:</label>
            <input type="file" name="pict" id="pict">
        </div>
        <button type="submit" class="btn btn-default">Insert</button>
    </form>
END;

    return $html;

}

?>