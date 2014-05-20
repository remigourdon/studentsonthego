<?php

include_once("inc/HTMLTemplate.php");

$breadcrumb=<<<END
<div class="container">
<div class="row">
   <div class="col-md-2 col-md-offset-1">
      <ol class="breadcrumb">
        <li><a href="About.php">About</a></li>
        <li class="active">Witness</li>
      </ol>
   </div>
</div>
</div>
END;

$content=<<<END
<div class="container">
   <br>
   <div class="col-md-8 col-md-offset-2">

      <h3>Why you might want to take this trip</h3>
      <p></p>





END;


echo $header;
echo $breadcrumb;
echo $content;
echo footer();


?>