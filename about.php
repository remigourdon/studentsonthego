<?php

include_once("inc/HTMLTemplate.php");


$jumbotron=<<<END
    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container">
        <h1>This is all about discovering ..</h1>
        <p></p>

        <p><a href="index.php#map" class="btn btn-primary btn-lg" role="button"><span class="glyphicon glyphicon-globe"></span> Take a tour</a></p>
      </div>
    </div>

    <div class="container">

      <div class="row">
        <div class="col-md-4">
          <h2>Conquer Europe with Erasmus !</h2>
          <p>Erasmus is today the most wellknown exchange program of Europe. Every year, thousands of students benefit it. And just because of that, you probably want to know more about it !</p>
          <p><a class="btn btn-default" href="erasmus.php" role="button">More details &raquo;</a></p>
        </div>

        <div class="col-md-4">
          <h2>Advices & usefull links</h2>
          <p>Preparing one or more semesters abroad is a lot to think about. It requires some organisation and forgetting some little things can become really annoying. Here is the good way of thinking your trip preparation.</p>
          <p><a class="btn btn-default" href="tips.php" role="button">More details &raquo</a></p>
       </div>


        <div class="col-md-4">
          <h2>Why you might want to take this trip.</h2>
          <p>You probably heard a lot of stories about exchange studies and the best way to know everything about it is of course living it ! But until then, here's a witness of a french student in Sweden !</p>
          <p><a class="btn btn-default" href="witness.php" role="button">More details &raquo</a></p>
        </div>
      </div>
   </div>
      <hr>
END;

echo $header;
echo $jumbotron;
echo footer();

?>