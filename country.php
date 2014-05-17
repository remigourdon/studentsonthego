<?php

/**
 * This page display every information about the country
 * the user selected using the map. It also display the map
 * of the country and some additional informations if any.
 */


include_once("inc/HTMLTemplate.php");
$id="";
$country="";
$currency="";
$lang="";
$primeMin="";
$capital="";

// Retrieve country name
if(!empty($_GET)) {

    include_once("inc/connstring.php");
    $table="studentsonthego";

    $id=$_GET['id'];

    //look for it in the DB
    $query= <<<END

--
-- Look for the given country
--
SELECT country
FROM $table
WHERE id = "{$id}";

END;

    // perform query and catch result
    // if query fails display error message with corresponding nb
    $res = $mysqli->query($query) or die ("could not query database" . $mysqli->errno . " : " . $mysqli->error);

    // if it match with a country in the DB
    if($res->num_rows == 1){
        $country = $num->country;
        $currency = $num->currency;
        $lang = $num->language;
        $primeMin = $num->primeMinister;
        $capital = $num->capital;
    }
}


// display the page corresponding
// to the wanted country
$content=<<<END

	<div class="container">
		<div class="row">
		
			<!-- INFO BLOC -->
			<div class="col-md-5" id ="bloc1">
				<p>$country </p>
				
				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-3 col-md-offset-1">
						<p>Language :</p>
					</div>
					
					<div class="col-md-3 col-md-offset-1">
						<p>$lang.</p>
					</div>
				</div>
				
				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-3 col-md-offset-1">
						<p>Capital :</p>
					</div>
					
					<div class="col-md-3 col-md-offset-1">
						<p>$capital.</p>
					</div>
				</div>
				
				<div class="row" id = "bloc1"> <!-- new info line -->
					<div class="col-md-3 col-md-offset-1">
						<p>Prime minister :</p>
					</div>
					
					<div class="col-md-3 col-md-offset-1">
						<p>$primeMin.</p>
					</div>
				</div>
				
			</div><!-- col-md-5 -->
			
			<!-- ------------- -->
			<!-- ---- MAP ---- -->
			<!-- ---------------->
			<div class="col-md-6 col-md-offset-1" id ="bloc1">
				<p>Add map here.</p>
			</div><!-- col-md-5 col-md-offset-1 -->
		
		</div><!-- row -->

		<div class="row"><br></div><!-- SEPARATOR -->
		
		<!-- additional content -->
		<div class="row" id = "bloc1"> <!-- new info line -->
			<div class="col-md-11 col-md-offset-1">
				<p>Additional content here if necessary</p>
			</div>
		</div>
		
	</div><!-- container -->


END;

echo $header;
echo $content;
echo $footer;

?>