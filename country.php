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

    $table="countries";

    $id=$_GET['id'];

    //look for it in the DB
    $query= <<<END

--
-- Look for the given country
--
SELECT name, currency, language, population, capital, AsText(geometry), callingCode
FROM {$table}
WHERE ID = {$id};

END;

    // perform query and catch result
    // if query fails display error message with corresponding nb
    $res = $mysqli->query($query) or die ("could not query database" . $mysqli->errno . " : " . $mysqli->error);

    // if it match with a country in the DB
    if($res->num_rows == 1){
    	$row = $res->fetch_array();
        // retrieve each properties of the country
        $country = $row['name'];
        $currency = $row['currency'];
        $lang = $row['language'];
        $popul = $row['population'];
        //$primeMin = $row['primeMinister'];
        $capital = $row['capital'];
        $primeMin = "";
        $callCode = $row['callingCode'];

        // Deliver json file
        include_once("inc/conversions.php");
        $properties = ["ID" => $id, "name" => $country, "population" => $popul];
        file_put_contents("./content/data.json", wkt_to_json(array($row['AsText(geometry)'] => $properties)));
    }
}

// if the form has been filled up
if(!empty($_POST)){
    $countryForm = isset($_POST["countryForm"]) ? $_POST["countryForm"] : '';
    $monthsForm = isset($_POST["monthsForm"]) ? $_POST["monthsForm"] : '';

    // dummy algorythm
    $result=(265 * $monthsForm);

    $feedback=<<<END


    <div class="col-md-6 col-md-offset-4">
        <p>Cost : {$result} €</p>
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
						<p>$capital</p>
					</div>
				</div>

				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-4 col-md-offset-1">
						<p>Prime minister :</p>
					</div>

					<div class="col-md-3 col-md-offset-1">
						<p>$primeMin</p>
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
END;

$query =<<<END
    SELECT ID, name
    FROM countries;
END;

$res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

// memorize the last passed country at the head of the combobox
if(!empty($_POST)){
    $calcform.="<option>$countryForm</option>";
}
else{// but if the user open the page for first time
    // just display the name of the country page
    $calcform.="<option>$country</option>";
}

while( $row = $res->fetch_array() ) {

    $nom=$row['name'];

    // don't display again the country at the head of the combobox
    if(isset($_POST) && $nom != $countryForm){
        $calcform.=<<<END
				   	<option>{$nom}</option>
END;
    }
}
$calcform.=<<<END

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