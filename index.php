<?php
include_once("inc/HTMLTemplate.php");

// Query database for countries informations
include_once("inc/conversions.php");

$query = "SELECT ID, name, population, AsText(geometry) FROM countries";

$res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

$data = array();

while($row = $res->fetch_array()) {

    $properties = array(
        "ID" => $row['ID'],
        "name" => $row['name'],
        "population" => $row['population']);

    $country = array($row['AsText(geometry)'] => $properties);

    $data = array_merge($data, $country);

}

file_put_contents("./content/json/country_global.json", wkt_to_json($data));


// Welcome panel
$welcPanel=<<<END
<main class="_head" id="content" role="main">
    <p class="lead">You will soon study abroad and you want to know how much it will cost you ? <br>You're in the right place !<p>
    <p style="text-align:right;"><em>Students on the go</em>. <strong>By</strong> students <strong>for</strong> students.</p>
</main>
END;

$welcPanel=<<<END
<div id="homeBanner" class="jumbotron">
  <h1>Want to study abroad ?</h1>
  <p><em>Students on the go</em> aim to help future exchange students by gathering some usefull informations about the country they want to study into.</p>
  <br>
  <p><a href="#map" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-globe"></span> Pick a country</a></p>
</div>
END;

// Map : center
$map=<<<END
<div class="container">
   <div class="col-md-12">
      <div id='map'></div>
   </div>
</div>
END;

$mapButtons=<<<END
<br>
<br>
<div class="row">
   <div class="col-md-3 col-md-offset-5">
      <div class="btn-toolbar">
         <a class="btn btn-primary" href="add-country.php">Add a country</a>
         <a class="btn btn-primary" href="del-country.php">Remove a country</a>
      </div>
   </div>
</div>
END;


echo $header;

echo $map;
// if user isn't admin
if( isset($_SESSION["username"])){ 
    echo $mapButtons;
}

echo footer("global");
?>
