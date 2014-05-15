<?php


$header=<<<END
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <!-- choose what version of IE the page should be rendered as -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Search engine tags -->
    <meta name="description" content="Students on the go, a place where future travelling students can estimate the price of their stay.">

    <!-- Authors  -->
    <meta name="author" content="Hichame Moriceau - Rémi Gourdon">

    <!-- Favicon  -->
    <link rel="icon" type="image/png" href="img/studyabroad.ico">

    <title>Students on the go !</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for the basis template -->
    <link href="starter-template.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->

    <!-- Specific features -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Search engine robots's tags -->
    <meta name="robots" content="index, follow">


    <style>
       #adminButton{
           color:#191919;// Hidden
       }
    </style>


  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">

        <a class="navbar-brand" href="index.php">Students on the go</a>

        </div> <!-- navbar-header -->


        <!-- Navigator's buttons  -->
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="calculform.php">Calculate the price of your stay !</a></li>

            <li class="active"><a href="about.php">About</a></li>
          </ul>        

     <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Countries <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li><a href="#">France</a></li>
            <li><a href="#">Italy</a></li>
            <li><a href="#">Slovakia</a></li>
            <li><a href="#">...</a></li>

<!--
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>

          </ul>
        </li> <!-- dropdown -->
      </ul><!-- nav navbar-nav navbar-right -->
     </div> <!-- collapse navbar collapse -->
    </div><!-- nav-bar header -->

  </div> <!-- container -->

</div> <!-- navbar -->
-->
<br><br><br><br> <!-- ugly -->
END;


$footer = <<<END

<div id="footer"> <!-- FIRST row of the footer  -->
<div class="row">
   <div class="col-md-12">

      <!-- FIRST HALF -->
      <div class="container">
        <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-copyright-mark"></span> Copyright</p>
        </div><!-- col-md-2 -->

        <div class="col-md-8"></div> <!-- separator -->

      <!-- SECOND HALF -->
        <div class="col-md-2">
        <p class="text-muted">Add W3C Validation</p>
       </div><!-- col-md-2 -->

      </div><!-- container -->

  </div><!-- col-md-12 -->
</div> <!-- row -->

<div class="row"><!-- SECOND row of the footer  -->
  <div class="col-md-12">

 <!-- FIRST HALF -->
      <div class="container">
        <div class="col-md-2">
        <a id="adminButton" href="add-country.php" >Admin</a>
        </div><!-- col-md-2 -->

        <div class="col-md-8"></div> <!-- separator -->

      <!-- SECOND HALF -->
        <div class="col-md-2">
        <p></p>
       </div><!-- col-md-2 -->

      </div><!-- container -->

     
  </div><!-- col-md-12 -->
</div><!-- row  -->

</div><!-- footer -->

    <!-- ===============Bootstrap core JavaScript================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html> 
END;

?>