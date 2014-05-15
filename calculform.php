<?php

include_once("inc/HTMLTemplate.php");

$calc_form=<<<END
<br><br><br><br><br><br>

<div class"container">

<form class="form-horizontal" role="form">
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
      <select id="select" class="form-control">
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
      <select id="select" class="form-control" >
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
           <button type="button" class="btn btn-default">Submit</button>
        </div>
    </div>

</form>
END;

$res = 1000;

$feedback=<<<END
<!-- ADD THE RESULT  -->

<br><br> <!-- ugly  -->


<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <p class="text-info">The cost of your stay is estimated to : {$res} €</p>
    </div>
</div>

</div> <!--container -->
END;



echo $header;
echo $calc_form;
echo $feedback;
echo $footer;


?>