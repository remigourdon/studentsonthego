<?php


$header=<<<END
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Search engine tags -->
    <meta name="description" content="Students on the go, a place where future travelling students can estimate the price of their stay.">

    <meta name="author" content="">
    <!--<link rel="shortcut icon" href="../../docs-assets/ico/favicon.png">-->

    <title>Starter Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="starter-template.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->

    <!-- Personnal stylesheet -->
    <link href="css/style.css" rel="stylesheet">
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Students on the go</a>
        </div> <!-- navbar-header -->
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
            <li class="divider"></li>
            <li><a href="#">Separated link</a></li>
          </ul>
        </li> <!-- dropdown -->
      </ul><!-- nav navbar-nav navbar-right -->
        </div> <!-- collapse navbar collapse -->
      </div><!-- nav-bar header -->

    </div> <!-- container -->

</div> <!-- navbar -->
END;


$footer = <<<END

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html> 
END;

?>