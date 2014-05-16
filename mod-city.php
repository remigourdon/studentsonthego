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
        $name = isset($_POST['name']) ? $_POST['name'] : "";
        $desc = isset($_POST['desc']) ? $_POST['desc'] : "";
        $lati = isset($_POST['lati']) ? $_POST['lati'] : "";
        $long = isset($_POST['long']) ? $_POST['long'] : "";

        // Check for the files
        $pict = (!file_exists($_FILES['pict']['tmp_name'])
                    || $_FILES['pict']['error'] > 0
                    || ($_FILES['pict']["type"] != "image/png"
                        && $_FILES['pict']["type"] != "image/jpg"
                        && $_FILES['pict']["type"] != "image/jpeg")) ? false : true;
        $feedback = "Please check the file(s): ";
        $feedback .= !$pict ? "`picture` " : "";

        // If some mandatory fields aren't filled in
        if($name == "" || $desc == "" || $lati == "" || $long == "") {

            // Give information
            $content .= "<p>Please complete all the mandatory fields.</p>";
            $content .= "<p>" . $feedback . "</p><hr>";
            $content .= getForm($id, $name, $desc, $lati, $long);

        // If everything is fine, we can proceed to the query
        } else {

            // Prevent SQL injections and encode UTF-8 characters
            $name       = utf8_encode($mysqli->real_escape_string($name));
            $desc       = utf8_encode($mysqli->real_escape_string($desc));

            // Prepare coordinates in wkt
            $wkt = "POINT({$lati} {$long})";

            $query = <<<END
            --
            -- Updates city in the database
            --
            UPDATE {$tableCities}
            SET name = '{$name}', description = '{$desc}', coordinates = PointFromText('{$wkt}')
            WHERE ID = {$id};
END;

            // Performs query
            $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);
            if($mysqli->affected_rows >= 1) {

                // Query was successful, we can save the files
                if($pict) {
                    // Gets extension
                    $pictExt    = "." . end((explode(".", $_FILES['pict']['name'])));

                    // Gets the directory path
                    $path = "./content/cities/{$name}/";

                    move_uploaded_file($_FILES['pict']['tmp_name'], $path . "picture" . $pictExt);
                }

                // Redirect the user
                header("Location: add-country.php");
                exit();

            // There was a problem during the update
            } else {

                // Provide new form and feedback
                $content .= "<p>Please try again.</p><hr>";
                $content .= getForm($id, $name, $desc, $lati, $long);

            }

        }

    // The user hasn't filled in the form yet
    } else {

        // We will query the database to get the current values
        $query = <<<END
        --
        -- Gets the current values in the database for the city
        --
        SELECT name, description, AsText(coordinates)
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
            $desc    = utf8_decode($city->description);

            // Decode wkt
            $array  = get_object_vars($city);
            $coord  = point_to_coord($array["AsText(coordinates)"]);

            $content .= "<p>Fill all the mandatory fields.</p><hr>";
            $content .= getForm($id, $name, $desc, $coord[0], $coord[1]);

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
echo $footer;


function getForm($id = "", $name = "", $desc = "", $lati = "", $long = "") {

    $name = htmlspecialchars($name);
    $desc = htmlspecialchars($desc);

    $html = <<<END
    <form role="form" action="mod-city.php?id={$id}" method="post" enctype="multipart/form-data">
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