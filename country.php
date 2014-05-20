<?php

/**
 * This page display every information about the country
 * the user selected using the map. It also display the map
 * of the country and some additional informations if any.
 */


include_once("inc/HTMLTemplate.php");

// country's properties
$id="";
$country="";
$currency="";
$lang="";
$primeMin="";
$capital="";
$capitalID="";
$callCode="";
$popul="";
$fastfood="";
$fitness="";
$cinema="";
$rent="";
$capitalName="";
// form variables
$feedback="";
$countryForm="";
$monthsForm="";

// Retrieve country name
if(!empty($_GET)) {

    $tableCountries = "countries";
    $tableCities = "cities";

    $id=$_GET['id'];

    //look for it in the DB
    $query= <<<END

--
-- Look for the given country
--
SELECT name, currency, language, population, capitalID, AsText(geometry),
        rent, fastfood, internet, transports, cinema, fitness, callingCode
FROM {$tableCountries}
WHERE ID = {$id};

END;

    // perform query and catch result
    // if query fails display error message with corresponding nb
    $res = $mysqli->query($query) or die ("could not query database" . $mysqli->errno . " : " . $mysqli->error);

    // if it match with a country in the DB
    if($res->num_rows == 1){
    	$row = $res->fetch_array();
        // retrieve each properties of the country
        $country = utf8_decode($row['name']);
        $currency = $row['currency'];
        $lang = $row['language'];
        $popul = $row['population'];
        //$primeMin = $row['primeMinister'];
        $capitalID = $row['capitalID'];
        $primeMin = "";
        $callCode = $row['callingCode'];
        $fitness=$row['fitness'];
        $fastfood=$row['fastfood'];
        $rent=$row['rent'];
        $cinema=$row['cinema'];

        // Get the cities associated to the country
        $queryCities = <<<END
        --
        -- Get the cities data associated to the country
        --
        SELECT ID, name, population, AsText(coordinates)
        FROM {$tableCities}
        WHERE countryID = {$id};
END;

        // Performs query
        $resCities = $mysqli->query($queryCities) or die ("could not query database" . $mysqli->errno . " : " . $mysqli->error);
        if($resCities->num_rows >= 1) {

            $dataCities = array();

            while($rowCity = $resCities->fetch_array()) {

                $propCities = array(
                    "ID"            => $rowCity['ID'],
                    "name"          => utf8_decode($rowCity['name']),
                    "population"    => $rowCity['population']);
                $city = array($rowCity['AsText(coordinates)'] => $propCities);
                $dataCities = array_merge($dataCities, $city);

                if($capitalID == $rowCity['ID']){
                    $capitalName = $rowCity['name'];
                }
            }
        }

        // Deliver json files
        include_once("inc/conversions.php");
        // Country file
        $properties = [
                "ID"            => $id,
                "name"          => $country,
                "population"    => $popul,
                "capitalID"     => $capitalID,
                "prices"        => [
                                        "rent"       => $row['rent'],
                                        "fastfood"   => $row['fastfood'],
                                        "internet"   => $row['internet'],
                                        "transports" => $row['transports'],
                                        "cinema"     => $row['cinema'],
                                        "fitness"    => $row['fitness']]];

        file_put_contents("content/json/country_{$id}.json", wkt_to_json(array($row['AsText(geometry)'] => $properties)));
        // Cities file
        file_put_contents("content/json/country_{$id}_cities.json", wkt_to_json($dataCities));
    }
}

// display the page corresponding
// to the wanted country
$content=<<<END
	<div class="container">
		<div class="row">

			<!-- INFO BLOC -->
			<div class="col-md-5" id ="bloc1">
				<p style="font-size:130%; text-align:center;"><strong>$country</strong></p>

				<div class="row" id = "bloc1"> <!-- new info line -->
                    <br>
					<div class="col-md-4 col-md-offset-1">
						<p>Language(s) :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>$lang</p>
					</div>
				</div>

				<div class="row" id = "bloc1"> <!-- new info line -->

					<div class="col-md-4 col-md-offset-1">
						<p>Capital :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>$capitalName</p>
					</div>
				</div>

				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-4 col-md-offset-1">
						<p> Inhabitants :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>$popul</p>
					</div>
				</div>

				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-4 col-md-offset-1">
						<p>Currency :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>$currency</p>
					</div>
                </div>

				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-4 col-md-offset-1">
						<p>Calling code :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>+$callCode</p>
					</div>
                </div>
                <br>
			</div><!-- col-md-5 -->

			<!-- ---- MAP ---- -->
			<div class="col-md-6 col-md-offset-1" id ="map"></div>

		</div><!-- row -->
END;
// if user isn't admin
if( isset($_SESSION["username"])){ 

$content.=<<<END
            <div class="col-md-5 col-md-offset-7">
                <div class="btn-toolbar" role="toolbar">
                   <a class="btn btn-primary" href="add-city.php">Add a city</a>
                   <a class="btn btn-primary" href="del-city.php">Remove a city</a></div>
                </div>
            </div>
		<div class="row"><br></div><!-- SEPARATOR -->
END;
}
else {
    $content.="<div class='row'><br></div><!-- SEPARATOR -->";
}
// if the form has been filled up
if(!empty($_POST)){
    $countryForm = isset($_POST["countryForm"]) ? $_POST["countryForm"] : '';
    $monthsForm = isset($_POST["monthsForm"]) ? $_POST["monthsForm"] : '';

/*
        // define query
        $quer = <<<END
        --
        -- Seek the corresponding country
        --
        --
        SELECT fitness, cinema, rent, fastfood, ID
        FROM countries
        WHERE name = "{$countryForm}";
END;


        $resu = $mysqli->query($quer) or die ("could not query database" . $mysqli->errno . " : " . $mysqli->error);

    $row = $resu->fetch_array();

    // Dummy algorythm
    $fitness = rand(20, 50);
    $fastfood = rand(5, 13);
    $rent = rand(150, 450);
    $cinema = rand(3,15);
    $result=($fitness + 2*$fastfood + $cinema + $rent) * $monthsForm;
*/

    $feedback=<<<END


    <div class="col-md-6 col-md-offset-4">
        <p>Cost : {$result} €</p>
    </div>

END;
}



$calcform=<<<END

    <div class="row"><br></div> <!-- separator -->

	<div class="row">
		<div id="bloc1" class="col-md-12">
			<div class="col-md-5"><!-- left bloc -->
				<img style="width:100%;" src="img/free_map.jpg" alt="Photo map of Europe" />
			</div><!-- left bloc -->

			<div id="bloc1" class="col-md-6 col-md-offset-1"><!-- right bloc -->
				<form id="dynamicForm" class="form-horizontal" role="form" action="country.php?id=$id" method="post">
					<br>
					<div class="row">

					  <div class="col-md-10 col-md-offset-2" >
						<legend class="formLegend">Estimate the price of your stay !</legend>
						<br>
					</div>

					<div class="row">
					  <div class="col-md-1" ></div>
					</div>
END;


// add more fields here
$calcform.=<<<END
                    <div class="row"><!-- 1st row -->
					  <div class="col-md-4 col-md-offset-1">
                         <label for="select">Number of fast food eating (per month) : </label>
					  </div>


					  <div class="input-group">
						 <input name="nbFastfood" type="text" class="form-control" placeholder="3">
                      </div>

                    </div> <!-- end of the row -->

                   <div class="row">
                      <div class="col-md-4 col-md-offset-1">
                         <label for="select">Will you go to the gym ?</label>
                      </div>
    
                      <div class="col-md-1"
                         <div class="input-group">
                            <span class="input-group-addon">
                              <input type="checkbox">
                            </span>
                         </div><!-- /input-group -->
                     </div><!-- /.col-md-6 -->
                  </div><!-- end of the row -->
END;



$calcform.=<<<END
					<div class="row">
					  <div class="col-md-1" ></div>
					</div>

					<div class="row"> <!-- ------SECOND ROW------  -->
					  <div class="col-md-4 col-md-offset-1">
						  <label for="select">Duration of the stay : </label>
					  </div>
					  <div class="col-md-5">

					  <div class="input-group">
						 <input name="monthsForm" type="text" class="form-control" placeholder="4.5">
						 <span class="input-group-addon">months</span>
					  </div>

					  </div>
					</div>

					<div class="row">
					  <div class="col-md-1" ><br></div>
					</div>

					<div class="row">
						<div class="col-md-2 col-md-offset-4">
						   <button type="submit" class="btn btn-default">Submit</button>
						   <br><br><br>
						</div>
					</div>

				</form>

			$feedback

		</div><!-- col-md-5 -->
		</div><!-- col-md-11 col-md-offset-1 -->
	</div><!-- row -->
</div><!-- container -->

END;


echo $header;
echo $content;
echo $calcform;
echo footer("country");


?>