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
        rent, fastfood, internet, transports, cinema, fitness, callingCode, beer
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
                                        "fitness"    => $row['fitness'],
                                        "beer"       => $row['beer']]];

        file_put_contents("content/json/country_{$id}.json", wkt_to_json(array($row['AsText(geometry)'] => $properties)));
        // Cities file
        file_put_contents("content/json/country_{$id}_cities.json", wkt_to_json($dataCities));
    }
}

$content=<<<END
<div class="container">
   <div class="row">

	  <!-- INFO BLOC -->
      <div class="col-md-5" id="contentBlocs">
END;

$infoBloc.=<<<END
                <!-- head of the info bloc  -->
<div id="countryHead" class="row">
   <div class="col-md-4 col-md-offset-1">
      <br>
      <p style="font-size:200%;">
         <strong>$country</strong>
      </p>
   </div>

   <div class="col-md-4 col-md-offset-1">
      <img style="width:100%;" src="content/countries/{$country}/flag.png" alt="Flag">
   </div>
</div><!-- end of : head of the info bloc  -->
<br>

END;

$dataCountry=<<<END
<div class="col-md-12">
   <br>
   <table id="countryProperties" style="width:300px">
   <tr>
      <td>Language(s)</td>
      <td>$lang</td>
   </tr>
   <tr>
      <td>Capital</td>
      <td>$capitalName</td>
   </tr>
   <tr>
       <td>Currency</td>
       <td>$currency</td>
   </tr>
   <tr>
       <td>inhabitants</td>
       <td>$popul</td>
   </tr>
   <tr>
       <td>Dialling code</td>
       <td>+$callCode</td>
   </tr>
</table>
<br>
</div>
<br>
END;


$content .= $infoBloc;
$content .= $dataCountry;


// display the page corresponding
// to the wanted country
$content.=<<<END

			</div><!-- close the dataCountry container -->

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

$calcform=<<<END

    <div class="row"><br></div> <!-- separator -->

	<div class="row">
		<div id="contentBlocs" class="col-md-12">

		    	<div id="resGraph" class="col-md-5 col-offset-3"><!-- left bloc -->
<br><br><br>
			      	<div id="resultGraph"></div>
			   </div><!-- left bloc -->

			<div id="contentBlocs" class="col-md-6 col-md-offset-1"><!-- right bloc -->
				<form id="dynamicForm" class="form-horizontal" role="form" action="country.php?id=$id" method="post">
					<br>
					<div class="row">

					  <div class="col-md-10 col-md-offset-2" >
						<legend id="EstimationFormLegend" class="formLegend">Estimate the price of your stay !</legend>
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
    <label for="select">How many time will you go to the cinema :</label>
					  </div>

                      <div class="col-md-7">
	    				 <div class="input-group">
						    <input id="nbCinema" type="number" min="0" class="form-control" value="2">
                         </div>
                      </div>
                    </div> <!-- end of the row -->

                    <!-- divider -->
                    <div class="row"><br></div>


                    <div class="row"><!-- 2nd row -->
					  <div class="col-md-4 col-md-offset-1">
                         <label for="select">How many fast food restaurants will you eat at ? <span class="formIndicator">(per month)</span></label>
					  </div>

                      <div class="col-md-7">
	    				 <div class="input-group">
						    <input id="nbFastfood" name="fastfood" min="0" type="number" class="form-control" value="3">
                         </div>
                      </div>
                    </div> <!-- end of the row -->


                    <!-- divider -->
                    <div class="row"><br></div>

                    <div class="row"><!-- 3rd row -->
					  <div class="col-md-4 col-md-offset-1">
                         <label for="select">Number of beers you might drink :<span class="formIndicator">(per month) </label>
					  </div>

                      <div class="col-md-7">
	    				 <div class="input-group">
						    <input id="nbBeer" name="beer" type="number" min="0" class="form-control" value="10">
                         </div>
                      </div>
                    </div> <!-- end of the row -->

                    <!-- divider -->
                    <div class="row"><br></div>


                   <div class="row"><!-- 4th row -->
                      <div class="col-md-4 col-md-offset-1">
                         <label for="select">Will you go to the gym ?</label>
                      </div>

                      <div class="col-md-5">
                         <div class="input-group">
                            <span id="radioButton" class="input-group-addon">

                              <div class="col-md-1">
                              <label for="select">Yes </label>
                              <input id="gymYes" type="radio" name="gym" value="yes">
                             </div>

                              <div class="col-md-1 col-md-offset-4">
                                 <label for="select">No </label>
                                 <input id="gymNo" type="radio" name="gym" value="no" checked>
                              </div>
                            </span>
                          </div><!-- /input-group -->
                       </div><!-- end of the row-->
                     </div>


                    <!-- divider -->
                    <div class="row"><br></div>


                   <div class="row"><!-- 5th row -->
                      <div class="col-md-4 col-md-offset-1">
                         <label for="select">Will you go use the public transports ?</label>
                      </div>

                      <div class="col-md-5">
                         <div class="input-group">
                            <span id="radioButton" class="input-group-addon">

                              <div class="col-md-1">
                              <label for="select">Yes </label>
                              <input id="transportYes" type="radio" name="publicTransport" value="yes">
                             </div>

                              <div class="col-md-1 col-md-offset-4">
                                 <label for="select">No </label>
                                 <input id="transportNo" type="radio" name="publicTransport" value="no" checked>
                              </div>
                            </span>
                         </div><!-- /input-group -->
                     </div><!-- /.col-md-4 offset1 -->
                    </div>
                    <!-- divider -->
                    <div class="row"><br></div>


                   <div class="row"><!-- 6th row -->
                      <div class="col-md-4 col-md-offset-1">
                         <label for="select">Will you subscribe to an internet acces ?</label>
                      </div>

                      <div class="col-md-5"
                         <div class="input-group">
                            <span id="radioButton" class="input-group-addon">

                              <div class="col-md-1">
                              <label for="select">Yes </label>
                              <input id="internetYes" type="radio" name="internet_nb" value="yes">
                             </div>

                              <div class="col-md-1 col-md-offset-4">
                                 <label for="select">No </label>
                                 <input id="internetNo" type="radio" name="internet_nb" value="no" checked>
                              </div>
                            </span>
                         </div><!-- /input-group -->
                     </div><!-- /.col-md-4 offset1 -->
                  </div><!-- end of the row -->
END;




$calcform.=<<<END
					<div class="row">
					  <div class="col-md-1" ></div>
					</div>

                    <!-- divider -->
                    <div class="row"><br></div>


					<div class="row"> <!-- ------last row------  -->
					  <div class="col-md-4 col-md-offset-1">
						  <label for="select">Duration of the stay : </label>
					  </div>
					  <div class="col-md-5">

					  <div class="input-group">
						 <input id="durationStay" name="monthsForm" type="text" class="form-control" value="4.5">
						 <span class="input-group-addon">months</span>
					  </div>

					  </div>
					</div>

					<div class="row">
					  <div class="col-md-1" ><br></div>
					</div>

				</form>



                <div class="col-md-7 col-md-offset-2">
                   <div id="costResultPanel" class="panel panel-default">
                      <div class="panel-body">
                         <p id="result"></p>
                      </div>
                   </div>
                </div>


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