<?php

include_once("inc/connstring.php");


$header=<<<END
<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <!-- choose what version of IE the page should be rendered as -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Search engine tags -->
    <meta name="description" content="Students on the go, a place where future travelling students can estimate the price of their stay.">
    <meta name="keywords" content="Europe, student, exchange, Erasmus, abroad, university">

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
           color:#191919;//Hide it!
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
                <!-- add new stuff to the nav here -->
           </ul>
END;

// Build the country dropdown
$header.=<<<END
           <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">Countries <b class="caret"></b></a>
                 <ul class="dropdown-menu">

END;


$query =<<<END
    SELECT ID, name
    FROM countries;
END;

$res = $mysqli->query($query) or die("Could not query database" . $mysqli->errno . " : " . $mysqli->error);

// row counter
$cpt=0;

// display the first 20 ountries of the DB
while( ( $row = $res->fetch_array() ) AND ($cpt < 15) ){
    $header .= "<li><a href='country.php?id={$row['ID']}'>{$row['name']}</a></li>";
    $cpt = $cpt + 1;
}

$header.=<<<END
                  <li class="divider"></li>
                  <li><a id="mapButton" href="index.php#map"><span class="glyphicon glyphicon-globe"></span> Map</a></li>


                </ul><!-- dropdown-menu -->
             </li> <!-- dropdown -->
           </ul><!-- nav navbar-nav navbar-right -->
END;

// admin session
session_start();
$admin="";

if(isset($_GET["log"])) {
    $_SESSION = array();
    session_unset();
    session_destroy();
}

// Admin panel
if(isset($_SESSION["username"])) {
    $admin="{$_SESSION["username"]}";
    $header.=<<<END

       <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><strong>$admin</strong> <b class="caret"></b></a>
         <ul class="dropdown-menu">
          <li><a id="logoutButton" href="index.php?log=out"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
         </ul>
        </li>
       </ul>
END;
}

$header.=<<<END


       </div> <!-- collapse navbar collapse -->
    </div><!-- nav-bar header -->

  </div> <!-- container -->
<br><br><br><br>
END;


function footer($map = "") {

  return <<<END

<div id="footer"> <!-- FIRST row of the footer  -->
<div class="row">
   <div class="col-md-12">

      <!-- FIRST HALF -->
      <div class="container">
        <div class="col-md-3">
        <p class="text-muted">Copyright <span class="glyphicon glyphicon-copyright-mark"></span> 2014 <br>Rémi Gourdon & Hichame Moriceau. All rights reserved.</p>
        </div><!-- col-md-3 -->

        <div class="col-md-6"></div> <!-- separator -->

      <!-- SECOND HALF -->
        <div class="col-md-3">
           <p class="text-muted">Web System Fundamentals<br>University of Halmstad/Sweden <a id="adminButton" href="admin.php" >Admin</a></p>
           
       </div><!-- col-md-3 -->

      </div><!-- container -->

  </div><!-- col-md-12 -->
</div> <!-- row -->

<div class="row"><!-- SECOND row of the footer  -->
  <div class="col-md-12">

 <!-- FIRST HALF -->
      <div class="container">
        <div class="col-md-2 col-md-offset-0">
            
        </div><!-- col-md-2 -->

        <div class="col-md-8"></div> <!-- separator -->

      <!-- SECOND HALF -->
        <div class="col-md-2">
           
       </div><!-- col-md-2 -->

      </div><!-- container -->


  </div><!-- col-md-12 -->
</div><!-- row  -->

</div><!-- footer -->

    <!-- ===============Bootstrap core JavaScript================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery-2.1.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <script src="js/d3.min.js"></script>
    <script src="js/{$map}-map.js"></script>
  </body>
</html>
END;
}

?>