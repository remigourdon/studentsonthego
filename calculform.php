<?php

include_once("inc/HTMLTemplate.php"); // Site Template
//include_once("inc/connstring.php");   // DB connection

$feedback = "";
$country = "";
$months = "";

//if user clicked submit
if(!empty($_POST) ) {

    // retrieve the two fields of data
    $country= isset($_POST['country']) ?  $_POST['country'] : '';
    $months = isset($_POST['months']) ?  $_POST['months'] : '';

    // query DB for related informations (Required to estimate the price)


    //dummy algorythm
    $res = 1000 * $months;

    $feedback=<<<END

<br><br> <!-- ugly  -->
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <p class="text-info">The cost of your stay is estimated to : {$res} €</p>
   </div>

</div> <!--container -->
END;
}


$calc_form=<<<END

<div class"container">
<form class="form-horizontal" role="form" action="calculform.php" method="post">
<br> <!-- ugly  -->
    <div class="row">

      <div class="col-md-6 col-md-offset-3" >
        <legend>Let's think ..</legend>
<br> <!-- ugly  -->
      </div>

    <div class="row">
      <div class="col-md-1 col-md-offset-3" ></div>
    </div>

    </div>
    <div class="row"> <!-- ------FIRST ROW------  -->
      <div class="col-md-2 col-md-offset-4" >
          <label for="select">Country : </label>
      </div>
      <div class="col-md-2">
      <select id="select" class="form-control" name="country">
        <option>France</option>
        <option>Greece</option>
        <option>Austria</option>
        <option>Germany</option>
        <!-- See that later -->
      </select>
      </div>
      <br>
    </div>

    <div class="row">
      <div class="col-md-1 col-md-offset-3" ></div>
    </div>

    <div class="row"> <!-- ------SECOND ROW------  -->
      <div class="col-md-2 col-md-offset-4">
          <label for="select">Duration of the stay (months): </label>
      </div>
      <div class="col-md-2">
      <select id="select" class="form-control" name="months">
        <option>1</option>
        <option>1.5</option>
        <option>2</option>
        <option>2.5</option>
        <option>3</option>
      </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-1" ><br></div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
           <button type="submit" class="btn btn-default">Submit</button>
        </div>
    </div>

</form>
END;



echo $header;
echo $calc_form;
echo $feedback;
echo $footer;


?>