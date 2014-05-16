<?php

/**
 * This page allows an administrator to delete a city in the database.
 */

include_once("inc/HTMLTemplate.php");
include_once("inc/functions.php");

// Tables
$tableCities    = "cities";

// Open content
$content = <<<END
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading">Delete a city</div>
        <div class="panel-body">
END;

// An id is specified in GET
if(isset($_GET['id']) && $_GET['id'] != "") {

    include_once("inc/connstring.php");

    $id = $_GET['id'];

    $query = <<<END
    --
    -- Deletes specified city from the database
    --
    DELETE FROM {$tableCities}
    WHERE ID = $id;
END;

    // Performs query
    $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

    // The city was successfully removed
    if($mysqli->affected_rows >= 1) {

        // Redirect the user
        header("Location: add-country.php");
        exit();

    // There was a problem
    } else
        $content .= "<p>Something went wrong and the city was not removed.</p>";

    $mysqli->close();

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

?>