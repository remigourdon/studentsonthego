<?php

/**
 * This page allows an administrator to delete a country in the database.
 */

include_once("inc/HTMLTemplate.php");

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
    <div class="panel panel-default">
        <div class="panel-heading">Delete a country</div>
        <div class="panel-body">
END;

// An id is specified in GET
if(isset($_GET['id']) && $_GET['id'] != "") {

    include_once("inc/connstring.php");

    $id = $_GET['id'];

    $query = <<<END
    --
    -- Deletes specified country from the database
    --
    DELETE FROM {$tableCountries}
    WHERE ID = $id;
END;

    // Performs query
    $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

    // The country was successfully removed
    if($mysqli->affected_rows >= 1) {

        // We now want to remove the associated cities
        $queryCities = <<<END
        --
        -- Removes the cities associated with the removed country
        --
        DELETE FROM {$tableCities}
        WHERE countryID = $id;
END;

        // Performs query
        $mysqli->query($queryCities) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

        // Redirect the user
        header("Location: add-country.php");
        exit();

    // There was a problem
    } else
        $content .= "<p>Something went wrong and the country was not removed.</p>";

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
echo footer();

?>