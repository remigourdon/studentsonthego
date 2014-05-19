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
        rent, fastfood, internet, transports, cinema, fitness
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
                                        "transports" => $row['transporst'],
                                        "cinema"     => $row['cinema'],
                                        "fitness"    => $row['fitness']]];

        file_put_contents("content/json/country_{$id}.json", wkt_to_json(array($row['AsText(geometry)'] => $properties)));
        // Cities file
        file_put_contents("content/json/country_{$id}_cities.json", wkt_to_json($dataCities));
    }
}

// if the form has been filled up
if(!empty($_POST)){
    $countryForm = isset($_POST["countryForm"]) ? $_POST["countryForm"] : '';
    $monthsForm = isset($_POST["monthsForm"]) ? $_POST["monthsForm"] : '';

    // dummy algorythm
    $res=1000*$monthsForm;

    $feedback=<<<END


    <div class="col-md-6 col-md-offset-3">
        <p class="text-info">The cost of your stay is estimated to : {$res} ¤</p>
    </div>

END;
}



// display the page corresponding
// to the wanted country
$content=<<<END

	<div class="container">
		<div class="row">

			<!-- INFO BLOC -->
			<div class="col-md-5" id ="bloc1">
				<p><strong>$country</strong></p>

				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-3 col-md-offset-1">
						<p>Language :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>$lang</p>
					</div>
				</div>

				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-3 col-md-offset-1">
						<p>Prime minister :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>$primeMin</p>
					</div>
				</div>

			</div><!-- col-md-5 -->


			<!-- ---- MAP ---- -->
			<div class="col-md-6 col-md-offset-1" id ="map"></div>

		</div><!-- row -->

		<div class="row"><br></div><!-- SEPARATOR -->

		<!-- additional content -->
		<div class="row" id = "bloc1"> <!-- new info line -->
			<div class="col-md-11 col-md-offset-1">
				<p>Additional content here if necessary</p>
			</div>
		</div>

END;

$calcform=<<<END

    <div class="row"><br></div> <!-- separator -->

	<div class="row">
		<div id="bloc1" class="col-md-12">
			<div class="col-md-5"><!-- left bloc -->
				<img style="width:100%;" src="img/free_map.jpg" alt="Photo map of Europe" />
			</div><!-- left bloc -->

			<div id="bloc1" class="col-md-6 col-md-offset-1"><!-- right bloc -->
				<form id="subbloc" class="form-horizontal" role="form" action="country.php" method="post">

					<br>
					<div class="row">

					  <div class="col-md-10 col-md-offset-2" >
						<legend class="formLegend">Estimate the price of your stay !</legend>
						<br>
					</div>

					<div class="row">
					  <div class="col-md-1" ></div>
					</div>

					</div>
					<div class="row"> <!-- ------FIRST ROW------  -->
					  <div class="col-md-4 col-md-offset-1" >
						  <label for="select">Country : </label>
					  </div>
					  <div class="col-md-5">
					  <select id="select" class="form-control" name="countryForm">
						<option>$country</option>
						<option>Greece</option>
						<option>Austria</option>
						<option>Germany</option>
						<!-- See that later -->
					  </select>
					  </div>
					  <br>
					</div>

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
						<div class="col-md-2 col-md-offset-5">
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