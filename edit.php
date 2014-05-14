<?php

$content = "";

if(!empty($_POST)) {
    $name   = isset($_POST['name']) ? $_POST['name'] : "";
    $map    = "";

    if(!file_exists($_FILES['map']['tmp_name'])) {
        echo "Please upload a file";
    } else if($_FILES['map']['error'] > 0) {
        echo "Error:" . $_FILES['map']['error'] . "<br>";
    } else if($_FILES["map"]["type"] != "application/json") {
        echo "Please upload a JSON file";
    } else {
        $map = file_get_contents($_FILES['map']['tmp_name']);

        echo $map;
    }
} else {
    $content .= <<<END
<form action="edit.php" method="post" enctype="multipart/form-data">
    <label for="map">Map:</label>
    <input type="file" name="map" id="map">
    <input type="submit" name="submit" value="Submit">
</form>
END;
}

echo $content;

?>