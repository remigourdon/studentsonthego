<?php
   include_once("inc/HTMLTemplate.php");

// Welcome panel
$welcPanel=<<<END
<main class="_head" id="content" role="main">
    <p class="lead">You will soon study abroad and you want to know how much it will cost you ? <br>You're in the right place !<p>
    <p style="text-align:right;  "><em>Students on the go</em>. <strong>By</strong> students <strong>for</strong> students.</p>
</main>
END;

// map
$map= "<div id='map'></div>";




echo $header;
echo $welcPanel;
echo $map;
echo $footer;
?>
